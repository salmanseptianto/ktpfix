<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BlangkoAvailability;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $blangkoStock = optional(BlangkoAvailability::orderByDesc('created_at')->first())->jumlah_total ?? 0;

        return view('users.index', compact('blangkoStock'));
    }

    public function documentRequest()
    {
        $desaList = [
            'Brebes',
            'Gandasuli',
            'Pasarbatang',
            'Limbangan Wetan',
            'Limbangan Kulon',
            'Randusanga Wetan',
            'Randusanga Kulon',
            'Kaligangsa Wetan',
            'Kaligangsa Kulon',
            'Krasak',
            'Padasugih',
            'Pemaron',
            'Pulosari',
            'Tengki',
            'Wangandalem',
            'Pagejugan',
            'Sigambir',
            'Kalimati',
            'Kaliwlingi',
            'Lembarawa',
            'Kedunguter',
            'Terlangu',
            'Banjaranyar'
        ];

        $stokBlangko = BlangkoAvailability::orderByDesc('created_at')->value('jumlah_total') ?? 0;

        return view('users.document.create', compact('desaList', 'stokBlangko'));
    }
}