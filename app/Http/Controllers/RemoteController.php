<?php

namespace App\Http\Controllers;

class RemoteController extends Controller
{
    protected $user;
    protected $permissions;
    protected $request;
    protected $page_data;
    protected $response;


    public function showFirstPage() {

            $this->page_data['user'] = 'долбоеб';
        $this->page_data['brands'] = [
            'audi' => [
                'A1', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q2', 'Q3', 'Q5', 'Q7', 'Q8', 'TT', 'R8', 'e-tron'
            ],
            'bmw' => [
                '1 series', '2 series', '3 series', '4 series', '5 series', '6 series', '7 series', '8 series',
                'X1', 'X2', 'X3', 'X4', 'X5', 'X6', 'X7', 'Z4', 'i3', 'i4', 'i7', 'i8'
            ],
            'chevrolet' => [
                'Aveo', 'Camaro', 'Captiva', 'Cobalt', 'Cruze', 'Epica', 'Impala', 'Lacetti', 'Malibu',
                'Orlando', 'Spark', 'Suburban', 'Tahoe', 'Trailblazer', 'Trax'
            ],
            'ford' => [
                'EcoSport', 'Edge', 'Escape', 'Explorer', 'F-150', 'Fiesta', 'Focus', 'Fusion', 'Kuga',
                'Mondeo', 'Mustang', 'Ranger', 'Taurus', 'Transit'
            ],
            'honda' => [
                'Accord', 'Civic', 'CR-V', 'Fit', 'HR-V', 'Insight', 'Jazz', 'Legend', 'Pilot', 'Stepwgn'
            ],
            'hyundai' => [
                'Accent', 'Creta', 'Elantra', 'Genesis', 'Getz', 'i30', 'i40', 'Kona', 'Palisade',
                'Santa Fe', 'Solaris', 'Sonata', 'Tucson', 'Venue'
            ],
            'kia' => [
                'Ceed', 'Cerato', 'K5', 'Mohave', 'Optima', 'Picanto', 'Rio', 'Seltos', 'Sorento',
                'Soul', 'Sportage', 'Stinger', 'Telluride'
            ],
            'lada' => [
                'Granta', 'Kalina', 'Largus', 'Niva', 'Priora', 'Vesta', 'XRAY'
            ],
            'lexus' => [
                'CT', 'ES', 'GS', 'GX', 'IS', 'LC', 'LS', 'LX', 'NX', 'RC', 'RX', 'UX'
            ],
            'mazda' => [
                '2', '3', '6', 'CX-3', 'CX-5', 'CX-7', 'CX-9', 'CX-30', 'MX-5'
            ],
            'mercedes' => [
                'A-class', 'B-class', 'C-class', 'E-class', 'G-class', 'GL-class', 'GLA', 'GLB', 'GLC',
                'GLE', 'GLK', 'GLS', 'S-class', 'V-class'
            ],
            'mitsubishi' => [
                'ASX', 'Eclipse Cross', 'Galant', 'Lancer', 'Outlander', 'Pajero', 'Pajero Sport'
            ],
            'nissan' => [
                'Almera', 'Juke', 'Leaf', 'Murano', 'Note', 'Pathfinder', 'Qashqai', 'Rogue',
                'Sentra', 'Teana', 'Terrano', 'X-Trail'
            ],
            'renault' => [
                'Arkana', 'Duster', 'Fluence', 'Kaptur', 'Koleos', 'Logan', 'Megane', 'Sandero', 'Symbol'
            ],
            'skoda' => [
                'Fabia', 'Kamiq', 'Karoq', 'Kodiaq', 'Octavia', 'Rapid', 'Superb'
            ],
            'toyota' => [
                'Auris', 'Avensis', 'Camry', 'Corolla', 'Highlander', 'Hilux', 'Land Cruiser',
                'Prado', 'RAV4', 'Supra', 'Yaris'
            ],
            'volkswagen' => [
                'Arteon', 'Golf', 'Jetta', 'Passat', 'Polo', 'Tiguan', 'Touareg', 'Touran'
            ],
            'volvo' => [
                'S60', 'S80', 'S90', 'V40', 'V60', 'V90', 'XC40', 'XC60', 'XC90'
            ]
        ];
            return view('welcome', $this->page_data);

    }
}
