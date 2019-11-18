<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\alternatif;
use App\alternatif_bobot;
use App\index_random_consistency;
use App\jumlah_nilai;
use App\kriteria;
use App\Kriteria_bobot;
use App\topik;
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

            $cekjumlah = jumlah_nilai::where('kriteria_id',$Kriteria->id)->first();
            // dd($cekjumlah);
            if($cekjumlah){
                $data = [
                    'jumlah_nilai' => $gettotal,
                    'rata_rata_nilai' => $getrata2,
                ];
                jumlah_nilai::where('id',$cekjumlah->id)->update($data);
            } else {
                $data = [
                    'topik_id' => $topikId,
                    'kriteria_id' => $Kriteria->id,
                    'jumlah_nilai' => $gettotal,
                    'rata_rata_nilai' => $getrata2,
                ];
                jumlah_nilai::create($data);
            }
        }

        return response()->json("", 200);
    }

    public function getKriteriaCR($topikId)
    {
        $Kriterias = Kriteria::where('topik_id',$topikId)->get();
        $lamda = 0;
        foreach ($Kriterias as $Kriteria) {
            $getTotalBobotKolom = Kriteria_bobot::where('topik_id',$topikId)->where('kriteria_id_kolom',$Kriteria->id)->sum('nilai_bobot');
            $getRata2 = jumlah_nilai::where('topik_id',$topikId)->where('kriteria_id',$Kriteria->id)->select('rata_rata_nilai')->first()->rata_rata_nilai;

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

            $cekjumlah = jumlah_nilai::where('alternatif_id',$alternatif->id)->first();
            if($cekjumlah){
                $data = [
                    'jumlah_nilai' => $gettotal,
                    'rata_rata_nilai' => $getrata2,
                ];
                jumlah_nilai::where('id',$cekjumlah->id)->update($data);
            } else {
                $data = [
                    'topik_id' => $topikId,
                    'alternatif_id' => $alternatif->id,
                    'jumlah_nilai' => $gettotal,
                    'rata_rata_nilai' => $getrata2,
                ];
                jumlah_nilai::create($data);
            }
        }

        return response()->json("", 200);
    }


    public function getAlternatifCR($topikId,$kriteriaId)
    {
        $alternatifs = alternatif::where('topik_id',$topikId)->get();
        $lamda = 0;
        foreach ($alternatifs as $alternatif) {
            $getTotalBobotKolom = alternatif_bobot::where('kriteria_id',$kriteriaId)->where('alternatif_id_kolom',$alternatif->id)->sum('nilai_bobot');
            $getRata2 = jumlah_nilai::where('topik_id',$topikId)->where('alternatif_id',$alternatif->id)->select('rata_rata_nilai')->first()->rata_rata_nilai;

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
}
