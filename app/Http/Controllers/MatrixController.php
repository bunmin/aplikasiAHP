<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\alternatif;
use App\alternatif_bobot;
use App\index_random_consistency;
use App\jumlah_nilai_alternatif;
use App\jumlah_nilai_kriteria;
use App\kriteria;
use App\Kriteria_bobot;
use App\topik;
use App\total_keseluruhan;
use DB;
use Auth;


class MatrixController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($topikId)
    {
        $user = Auth::user();
        $topik = topik::where('id',$topikId)->first();
        $alterntifs = alternatif::where('topik_id',$topikId)->get();
        $kriterias = kriteria::where('topik_id',$topikId)->get();

        return view('matrix.index', [
            'user' => $user,
            'class' => "topik",
            'title' => "Matrix Perbandingan",
            'topik' => $topik,
            'alterntifs' => $alterntifs,
            'kriterias' => $kriterias,
            'cekKriteriaHasil' => $this->cekKriteriaHasil($topik->id),
        ]);
    }

    public function matrixKriteria($topikId)
    {
        $user = Auth::user();
        $topik = topik::where('id',$topikId)->first();
        $kriterias = kriteria::where('topik_id',$topikId)->get();
        $kriteriaCount = kriteria::where('topik_id',$topikId)->count();
        $kriteriaBobotCount = Kriteria_bobot::where('topik_id',$topikId)->count();

        if ($kriteriaCount > $kriteriaBobotCount){
            Kriteria_bobot::where('topik_id',$topikId)->delete();
            foreach ($kriterias as $kriteria) {
                $kriterias2 = kriteria::where('topik_id',$topikId)->get();
                foreach ($kriterias2 as $kriteria2) {
                    $data = [
                        'user_id' => auth::user()->id,
                        'topik_id' => $topikId,
                        'kriteria_id_baris' => $kriteria->id,
                        'kriteria_id_kolom' => $kriteria2->id,
                        'nilai_bobot' => ($kriteria->id == $kriteria2->id ? 1 : 0),
                        'nilai_eigen' => 0,
                    ];
                    Kriteria_bobot::create($data);
                }
            }
        }

        return view('matrix.kriteria', [
            'user' => $user,
            'class' => "topik",
            'title' => "Matrix Kriteria",
            'topik' => $topik,
            'kriterias' => $kriterias,
        ]);
    }

    public function KriteriaGetAll($topikId)
    {
        $kriterias = kriteria::where('topik_id',$topikId)->get();

        $html = "<tr>
                    <td>Kriteria</td>";
                foreach ($kriterias as $kriteria) {
                    $html .= '<td><span style="font-weight:bold">'.$kriteria->nama.'</span></td>';
                };
        $html .= "</tr>";
        foreach ($kriterias as $kriteria) {
            $html .= '<tr>
                        <td><span style="font-weight:bold">'.$kriteria->nama.'</span></td>';
                    $matrixKriteriaKoloms = Kriteria_bobot::where('kriteria_id_baris',$kriteria->id)->select('id','nilai_bobot','kriteria_id_kolom',DB::raw('(select nama from kriterias where id = kriteria_bobots.kriteria_id_kolom) kriteria_nama_kolom'))->orderby('kriteria_id_kolom','asc')->get();
                    foreach ($matrixKriteriaKoloms as $matrixKriteriaKolom) {
                        $html .= '<td>'.$matrixKriteriaKolom->nilai_bobot.'</td>';
                    };
            $html .= '</tr>';
        };

        return response()->json($html, 200);
    }

    public function KriteriaGetAnother(Request $request,$topikId)
    {
        $kriterias = kriteria::where('topik_id',$topikId)->where('id','!=',$request->kriteriaid)->get();

        return response()->json($kriterias, 200);
    }

    public function updateKriteriaBobot(Request $request,$topikId)
    {
        if ($request->nilaibobot < 0){
            $nilaibobot = 1 / abs($request->nilaibobot);
        } else {
            $nilaibobot = abs($request->nilaibobot);
        };

        $data = [
            'nilai_bobot' => $nilaibobot,
        ];
        Kriteria_bobot::where('topik_id',$topikId)->where('kriteria_id_baris',$request->bobotkiri)->where('kriteria_id_kolom',$request->bobotkanan)->update($data);


        if ($request->nilaibobot < 0){
            $nilaibobot = abs($request->nilaibobot);
        } else {
            $nilaibobot = 1 / abs($request->nilaibobot);
        };

        $data = [
            'nilai_bobot' => $nilaibobot,
        ];
        Kriteria_bobot::where('topik_id',$topikId)->where('kriteria_id_baris',$request->bobotkanan)->where('kriteria_id_kolom',$request->bobotkiri)->update($data);

        $Kriteria_bobots = Kriteria_bobot::where('topik_id',$topikId)->get();
        foreach ($Kriteria_bobots as $Kriteria_bobot) {
            $gettotal = Kriteria_bobot::where('topik_id',$topikId)->where('kriteria_id_kolom',$Kriteria_bobot->kriteria_id_kolom)->sum('nilai_bobot');
            $geteigen = $Kriteria_bobot->nilai_bobot / $gettotal;

            $data = [
                'nilai_eigen' => $geteigen,
            ];
            Kriteria_bobot::where('id',$Kriteria_bobot->id)->update($data);
        }

        $kriteriaCount = kriteria::where('topik_id',$topikId)->count();
        $Kriterias = Kriteria::where('topik_id',$topikId)->get();
        foreach ($Kriterias as $Kriteria) {
            $gettotal = Kriteria_bobot::where('topik_id',$topikId)->where('kriteria_id_baris',$Kriteria->id)->sum('nilai_eigen');
            $getrata2 = $gettotal/$kriteriaCount;

            $cekjumlah = jumlah_nilai_kriteria::where('kriteria_id',$Kriteria->id)->first();
            if($cekjumlah){
                $data = [
                    'jumlah_nilai' => $gettotal,
                    'rata_rata_nilai' => $getrata2,
                ];
                jumlah_nilai_kriteria::where('id',$cekjumlah->id)->update($data);
            } else {
                $data = [
                    'user_id' => auth::user()->id,
                    'topik_id' => $topikId,
                    'kriteria_id' => $Kriteria->id,
                    'jumlah_nilai' => $gettotal,
                    'rata_rata_nilai' => $getrata2,
                ];
                jumlah_nilai_kriteria::create($data);
            }
        }

        $this->hitungTotal($topikId);

        return response()->json("", 200);
    }

    public function getKriteriaCR($topikId)
    {
        $Kriterias = Kriteria::where('topik_id',$topikId)->get();
        $lamda = 0;
        foreach ($Kriterias as $Kriteria) {
            $getTotalBobotKolom = Kriteria_bobot::where('topik_id',$topikId)->where('kriteria_id_kolom',$Kriteria->id)->sum('nilai_bobot');
            $getRata2 = jumlah_nilai_kriteria::where('topik_id',$topikId)->where('kriteria_id',$Kriteria->id)->select('rata_rata_nilai')->first()->rata_rata_nilai;

            $jumlahPerKriteria = $getTotalBobotKolom * $getRata2;
            $lamda = $lamda + $jumlahPerKriteria;
        }

        $kriteriaCount = kriteria::where('topik_id',$topikId)->count();
        $getIR = index_random_consistency::where('ukuran_metrix',$kriteriaCount)->first();
        $IR = $getIR->nilai;
        $CI = ($lamda - $kriteriaCount)/($kriteriaCount - 1);
        $CR = $CI/$IR;

        return response()->json($CR, 200);
    }

    public function cekKriteriaHasil($topikId)
    {
        $cek = Kriteria_bobot::where('topik_id',$topikId)->first();
        if ($cek) {
            $count = Kriteria_bobot::where('topik_id',$topikId)->where('nilai_bobot',0)->count();
        } else {
            //sebenarnya datanya kosong, tapi untuk tujuan validasi kita kasih nilai ke count
            $count = 1;
        }

        // return response()->json($count, 200);
        return $count;
    }

    public function getKriteriaNilai($topikId)
    {
        $jumlah_nilai = jumlah_nilai_kriteria::where('jumlah_nilai_kriterias.topik_id',$topikId)
                        ->join('kriterias','kriterias.id','=','jumlah_nilai_kriterias.kriteria_id')
                        ->select('kriterias.nama','jumlah_nilai_kriterias.rata_rata_nilai')
                        ->get();

        return response()->json($jumlah_nilai, 200);
    }



    public function matrixAlternatif($topikId,$kriteriaId)
    {
        $user = Auth::user();
        $topik = topik::where('id',$topikId)->first();
        $kriteria = kriteria::where('id',$kriteriaId)->first();
        $alternatifs = alternatif::where('topik_id',$topikId)->get();
        $alternatifCount = alternatif::where('topik_id',$topikId)->count();
        $alternatifBobotCount = alternatif_bobot::where('kriteria_id',$kriteriaId)->count();

        if ($alternatifCount > $alternatifBobotCount){
            alternatif_bobot::where('kriteria_id',$kriteriaId)->delete();
            foreach ($alternatifs as $alternatif) {
                $alternatifs2 = alternatif::where('topik_id',$topikId)->get();
                foreach ($alternatifs2 as $alternatif2) {
                    $data = [
                        'user_id' => auth::user()->id,
                        'topik_id' => $topikId,
                        'kriteria_id' => $kriteriaId,
                        'alternatif_id_baris' => $alternatif->id,
                        'alternatif_id_kolom' => $alternatif2->id,
                        'nilai_bobot' => ($alternatif->id == $alternatif2->id ? 1 : 0),
                        'nilai_eigen' => 0,
                    ];
                    alternatif_bobot::create($data);
                }
            }
        }

        return view('matrix.alternatif', [
            'user' => $user,
            'class' => "topik",
            'title' => "Matrix Alternatif",
            'topik' => $topik,
            'alternatifs' => $alternatifs,
            'kriteria' => $kriteria,
        ]);
    }

    public function AlternatifGetAll($topikId,$kriteriaId)
    {
        $alternatifs = alternatif::where('topik_id',$topikId)->get();

        $html = "<tr>
                    <td></td>";
                foreach ($alternatifs as $alternatif) {
                    $html .= '<td><span style="font-weight:bold">'.$alternatif->nama.'</span></td>';
                };
        $html .= "</tr>";
        foreach ($alternatifs as $alternatif) {
            $html .= '<tr>
                        <td><span style="font-weight:bold">'.$alternatif->nama.'</span></td>';
                    $matrixAlternatifKoloms = alternatif_bobot::where('kriteria_id',$kriteriaId)->where('alternatif_id_baris',$alternatif->id)->select('id','nilai_bobot','alternatif_id_kolom',DB::raw('(select nama from alternatifs where id = alternatif_bobots.alternatif_id_kolom) alternatif_nama_kolom'))->orderby('alternatif_id_kolom','asc')->get();
                    foreach ($matrixAlternatifKoloms as $matrixAlternatifKolom) {
                        $html .= '<td>'.$matrixAlternatifKolom->nilai_bobot.'</td>';
                    };
            $html .= '</tr>';
        };

        return response()->json($html, 200);
    }

    public function AlternatifGetAnother(Request $request,$topikId)
    {
        $alternatifs = alternatif::where('topik_id',$topikId)->where('id','!=',$request->alternatifid)->get();

        return response()->json($alternatifs, 200);
    }

    public function updateAlternatifBobot(Request $request,$topikId,$kriteriaId)
    {
        if ($request->nilaibobot < 0){
            $nilaibobot = 1 / abs($request->nilaibobot);
        } else {
            $nilaibobot = abs($request->nilaibobot);
        };

        $data = [
            'nilai_bobot' => $nilaibobot,
        ];
        Alternatif_bobot::where('kriteria_id',$kriteriaId)->where('alternatif_id_baris',$request->bobotkiri)->where('alternatif_id_kolom',$request->bobotkanan)->update($data);


        if ($request->nilaibobot < 0){
            $nilaibobot = abs($request->nilaibobot);
        } else {
            $nilaibobot = 1 / abs($request->nilaibobot);
        };

        $data = [
            'nilai_bobot' => $nilaibobot,
        ];
        alternatif_bobot::where('kriteria_id',$kriteriaId)->where('alternatif_id_baris',$request->bobotkanan)->where('alternatif_id_kolom',$request->bobotkiri)->update($data);

        $alternatif_bobots = alternatif_bobot::where('kriteria_id',$kriteriaId)->get();
        foreach ($alternatif_bobots as $alternatif_bobot) {
            $gettotal = alternatif_bobot::where('kriteria_id',$kriteriaId)->where('alternatif_id_kolom',$alternatif_bobot->alternatif_id_kolom)->sum('nilai_bobot');
            $geteigen = $alternatif_bobot->nilai_bobot / $gettotal;

            $data = [
                'nilai_eigen' => $geteigen,
            ];
            alternatif_bobot::where('id',$alternatif_bobot->id)->update($data);
        }

        $alternatifCount = alternatif::where('topik_id',$topikId)->count();
        $alternatifs = alternatif::where('topik_id',$topikId)->get();
        foreach ($alternatifs as $alternatif) {
            $gettotal = alternatif_bobot::where('kriteria_id',$kriteriaId)->where('alternatif_id_baris',$alternatif->id)->sum('nilai_eigen');
            $getrata2 = $gettotal/$alternatifCount;

            $cekjumlah = jumlah_nilai_alternatif::where('kriteria_id',$kriteriaId)->where('alternatif_id',$alternatif->id)->first();
            if($cekjumlah){
                $data = [
                    'jumlah_nilai' => $gettotal,
                    'rata_rata_nilai' => $getrata2,
                ];
                jumlah_nilai_alternatif::where('id',$cekjumlah->id)->update($data);
            } else {
                $data = [
                    'user_id' => auth::user()->id,
                    'topik_id' => $topikId,
                    'kriteria_id' => $kriteriaId,
                    'alternatif_id' => $alternatif->id,
                    'jumlah_nilai' => $gettotal,
                    'rata_rata_nilai' => $getrata2,
                ];
                jumlah_nilai_alternatif::create($data);
            }
        }

        $this->hitungTotal($topikId);

        return response()->json("", 200);
    }


    public function getAlternatifCR($topikId,$kriteriaId)
    {
        $alternatifs = alternatif::where('topik_id',$topikId)->get();
        $lamda = 0;
        foreach ($alternatifs as $alternatif) {
            $getTotalBobotKolom = alternatif_bobot::where('kriteria_id',$kriteriaId)->where('alternatif_id_kolom',$alternatif->id)->sum('nilai_bobot');
            $getRata2 = jumlah_nilai_alternatif::where('topik_id',$topikId)->where('kriteria_id',$kriteriaId)->where('alternatif_id',$alternatif->id)->select('rata_rata_nilai')->first()->rata_rata_nilai;

            $jumlahPerAlternatif = $getTotalBobotKolom * $getRata2;
            $lamda = $lamda + $jumlahPerAlternatif;
        }

        $alternatifCount = alternatif::where('topik_id',$topikId)->count();
        $getIR = index_random_consistency::where('ukuran_metrix',$alternatifCount)->first();
        $IR = $getIR->nilai;
        $CI = ($lamda - $alternatifCount)/($alternatifCount - 1);
        $CR = $CI/$IR;

        return response()->json($CR, 200);
    }

    public static function cekAlternatifHasil($topikId,$kriteriaId)
    {
        $cek = alternatif_bobot::where('topik_id',$topikId)->where('kriteria_id',$kriteriaId)->first();
        if ($cek) {
            $count = alternatif_bobot::where('topik_id',$topikId)->where('kriteria_id',$kriteriaId)->where('nilai_bobot',0)->count();
        } else {
            $count = 1;
        }

        // return response()->json($count, 200);
        return $count;
    }

    public function getAlternatifNilai(Request $request,$topikId)
    {
        $kriteriaId = $request->kriteriaId;
        $jumlah_nilai = jumlah_nilai_alternatif::where('jumlah_nilai_alternatifs.topik_id',$topikId)
                        ->where('jumlah_nilai_alternatifs.kriteria_id',$kriteriaId)
                        ->join('alternatifs','alternatifs.id','=','jumlah_nilai_alternatifs.alternatif_id')
                        ->select('alternatifs.nama','jumlah_nilai_alternatifs.rata_rata_nilai')
                        ->get();

        return response()->json($jumlah_nilai, 200);
    }

    public function hitungTotal($topikId)
    {
        $kriteriaCount = kriteria::where('topik_id',$topikId)->count();
        $alternatifCount = alternatif::where('topik_id',$topikId)->count();

        $kriteriaCount2 = $kriteriaCount * $kriteriaCount;

        $alternatifCount2 = $alternatifCount * $alternatifCount;
        $alternatifCount3 = $alternatifCount2 * $kriteriaCount;
        $cekAlternatif = alternatif_bobot::where('topik_id',$topikId)->where('nilai_bobot',0)->count();


        $kriteriaBobotCount = Kriteria_bobot::where('topik_id',$topikId)->count();
        $alternatifBobotCount = alternatif_bobot::where('topik_id',$topikId)->count();

        if ( $kriteriaCount2 == $kriteriaBobotCount && $this->cekKriteriaHasil($topikId) == 0 && $alternatifCount3 == $alternatifBobotCount && $cekAlternatif == 0){
            $alternatifs = alternatif::where('topik_id',$topikId)->get();
            foreach ($alternatifs as $alternatif) {
                $total = 0;
                $jumlah_alternatifs = jumlah_nilai_alternatif::where('topik_id',$topikId)->where('alternatif_id',$alternatif->id)->get();
                foreach ($jumlah_alternatifs as $jumlah_alternatif) {
                    $rata2kriteria = jumlah_nilai_kriteria::where('kriteria_id',$jumlah_alternatif->kriteria_id)->first()->rata_rata_nilai;
                    $rata2alternatif = $jumlah_alternatif->rata_rata_nilai;

                    $total = $total + ($rata2kriteria*$rata2alternatif);
                }

                $total_keseluruhan = total_keseluruhan::where('topik_id',$topikId)->where('alternatif_id',$alternatif->id)->first();
                if ($total_keseluruhan){
                    $data = [
                        'nilai_bobot' => $total,
                    ];
                    total_keseluruhan::where('id',$total_keseluruhan->id)->update($data);
                } else {
                    $data = [
                        'user_id' => auth::user()->id,
                        'topik_id' => $topikId,
                        'alternatif_id' => $alternatif->id,
                        'nilai_bobot' => $total,
                    ];
                    total_keseluruhan::create($data);
                }
            }
        }
    }

    public static function getTotal(Request $request)
    {
        $total_keseluruhans = total_keseluruhan::where('total_keseluruhans.topik_id',$request->topikId)
                            ->join('alternatifs','alternatifs.id','=','total_keseluruhans.alternatif_id')
                            ->select('alternatifs.nama','total_keseluruhans.nilai_bobot')
                            ->get();

        // echo total_keseluruhan::where('topik_id',$request->topikId)->sum('nilai_bobot');
        // die();

        return response()->json($total_keseluruhans, 200);
        // return $count;
    }
}
