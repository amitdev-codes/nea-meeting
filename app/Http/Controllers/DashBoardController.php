<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Modules\Groups\Models\Group;
use App\Models\CumulativeProgress;
use App\Services\DashBoardService;
use Illuminate\Support\Facades\App;
use Illuminate\Http\RedirectResponse;
use Modules\Groups\Models\GroupMember;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;


class DashBoardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashBoardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    public function welcome(Request $request): View
    {
        return view('welcome');
    }
    public function dashboard()
    {


        return view('pages.dashboard');
    }


    public function locale(Request $request): RedirectResponse
    {
        $locale = $request->query('locale');
        if (in_array($locale, array_keys(config('app.available_locales')))) {
            $request->user()->update(['locale' => $locale]);
            session(['locale' => $locale]);
            App::setLocale($locale);
        } else {
            return back()->with('notification', ['icon' => 'error', 'title' => __('menu.locale'), 'message' => __('notification.locale_not_available')]);
        }
        return back()->with('notification', ['icon' => 'success', 'title' => __('menu.locale'), 'message' => __('notification.locale_success')]);
    }

        
    private function getDashboardSettings()
    {
        return [
            'showWelcome' => true,
            'showUserLog' => true,
            'showProgressForm' => true,
        ];
    }
    
    private function getInfoCards()
    {
        return [
            [
                'title' => 'Rules and Regulations',
                'content' => '<p>Content for rules and regulations goes here.</p>',
                'width' => '4'
            ],
            [
                'title' => 'Publications',
                'content' => '<p>Content for publications goes here.</p>',
                'width' => '4'
            ],
            [
                'title' => 'FAQ',
                'content' => '<p>Content for FAQ goes here.</p>',
                'width' => '4'
            ],
        ];
    }
    
    private function getGalleryImages()
    {
        $images = [];
        for ($i = 1; $i <= 8; $i++) {
            $images[] = [
                'path' => 'img/gallery/' . $i . '.jpg',
                'alt' => 'Gallery Image ' . $i
            ];
        }
        return $images;
    }
    
    private function getCountryDetails()
    {
        return [
            'आयोजना क्लष्‍टर इकाई, सप्तरी',
            'आयोजना क्लष्‍टर इकाई, धनुषा',
            'आयोजना क्लष्‍टर इकाई, सिन्धुपाल्चोक',
            'Food and Nutrition Security Enhancement Project',
            'आयोजना क्लष्‍टर इकाई, गोरखा'
        ];
    }
    
    private function generateChartData()
    {
        // Mock chart data - in a real app, this would come from database
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            'data' => [10, 20, 15, 25, 30]
        ];
    }
    private function getAboutSection()
    {
        return [
            'title' => 'Who We Are',
            'content' => 'Food and Nutrition Security Enhancement Project (FANSEP) is funded by Global Agriculture and Food Security Program (GAFSP). The grant agreement between Government of Nepal (GON) and International Development Association (IDA) for the FANSEP was signed on 1st December 2023. The total Project cost is 22 million USD of which 20 million USD is GAFSP grant and 2 million USD is counterpart funding from GON. This project is implemented by Ministry of Agriculture and Livestock Development (MoALD), supervised by World Bank (WB) and Technical Assistance is provided by Food and Agriculture Organizations (FAO) of the United Nations. This project is implemented in 16 Rural Municipalities (RMs) of eight districts (Gorkha, Dhading, Sindhupalchowk, Dolakha, Dhanusha, Mahottari, Siraha and Saptari) for the duration of 3.5 years. The project aims to reach 55,000 direct beneficiaries.'
        ];
    }
    
    private function getOfficials()
    {
        return [
            ['name' => 'Dr. Arun Kafle', 'title' => 'Project Director', 'img' => 'project_director.png'],
            ['name' => 'Dr. Tapendra Bahadur Shah', 'title' => 'Information Officer', 'img' => 'information_officer.jpg'],
            ['name' => 'Deepak Poudel', 'title' => 'Nodal Officer', 'img' => 'nodal_officer.jpg'],
        ];
    }
    private function getCarouselItems()
    {
        // In a real application, you might fetch this from database
        return [
            [
                'image' => 'assets/img/illustrations/login.jpg',
                'title' => 'Food and Nutrition Security Enhancement Project (FANSEP)',
                'description' => 'is funded by Global Agriculture and Food Security Program (GAFSP).',
                'alt' => 'FANSEP Banner'
            ],
            [
                'image' => 'assets/img/illustrations/banner1.jpg',
                'title' => 'Food and Nutrition Security Enhancement Project (FANSEP)',
                'description' => 'is funded by Global Agriculture and Food Security Program (GAFSP).',
                'alt' => 'FANSEP Banner'
            ],
            [
                'image' => 'assets/img/illustrations/login.png',
                'title' => 'Food and Nutrition Security Enhancement Project (FANSEP)',
                'description' => 'is funded by Global Agriculture and Food Security Program (GAFSP).',
                'alt' => 'FANSEP Banner'
            ],
        ];
    }
}
