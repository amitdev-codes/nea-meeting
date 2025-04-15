<?php

namespace App\Services;


use Carbon\Carbon;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Modules\Groups\Models\Group;
use App\Models\CumulativeProgress;
use Modules\Groups\Models\GroupMember;

class DashBoardService
{
    protected $colorPalette = [
        '#696cff', '#ff3e1d', '#03c3ec', '#71dd37', '#ffab00', '#6f42c1', '#e83e8c',
    ];

    public function getDashboardData()
    {
        // Total Groups
        $totalGroups = Group::count();

        // Beneficiaries Estimated vs Reached
        $totalBeneficiariesEstimated = GroupMember::count();
        $totalBeneficiariesReached = 20; // Hardcoded for now

        // Cumulative Progress
        $progress = CumulativeProgress::first() ?? (object)[
            'project_start_date' => Carbon::parse('2023-01-01'),
            'project_end_date' => Carbon::parse('2025-12-31'),
            'total_estimated_expenditure' => 1000000,
            'total_given_expenditure' => 600000,
            'total_budget' => 1200000,
            'total_disbursed' => 500000,
            'total_group_formed_target' => 100,
        ];

        // Time Progress
        $totalDays = $progress->project_start_date->diffInDays($progress->project_end_date);
        $elapsedDays = (int)$progress->project_start_date->diffInDays(Carbon::now());
        $timeElapsedPercentage = round(($elapsedDays / $totalDays) * 100, 2);

        // Expenditure and Disbursement
        $spentFormatted = 'Rs ' . number_format($progress->total_given_expenditure, 0, '.', ',');
        $totalExpenditureFormatted = 'Rs ' . number_format($progress->total_estimated_expenditure, 0, '.', ',');
        $expenditureDisplay = "$spentFormatted / $totalExpenditureFormatted";
        $expenditurePercentage = round(($progress->total_given_expenditure / $progress->total_estimated_expenditure) * 100, 2);

        $disbursedFormatted = 'Rs ' . number_format($progress->total_disbursed, 0, '.', ',');
        $totalBudgetFormatted = 'Rs ' . number_format($progress->total_budget, 0, '.', ',');
        $disbursementDisplay = "$disbursedFormatted / $totalBudgetFormatted";
        $disbursementPercentage = round(($progress->total_disbursed / $progress->total_budget) * 100, 2);

        // Groups or dashbord first section
        $targetGroups = $progress->total_group_formed_target ?? 100;
        $achievedGroups = $totalGroups;
        $getGroupsBySectorAndSubSector = $this->getGroupsBySectorAndSubSector();
        // GroupMembers or dashbord second section
        $groupMembersBySectorGender = $this->getGroupMembersBySectorGender();
        $groupMembersBySubSectorGender = $this->getGroupMembersBySubSectorGender();
        $groupMembershipByGender = $this->getGroupMembershipByGender();
        $groupMembershipByCaste = $this->getGroupMembershipByCaste();
        // dd($groupMembersBySectorGender);
          // Beneficiaries Reached  or dashbord third section
        $beneficiariesReachedBySectorGender = $this->getBeneficiariesBySectorGender();
        $beneficiariesReachedBySubSectorGender = $this->getBeneficiariesBySubSectorGender();
        $beneficiariesReachedByGender = $this->getBeneficiariesReachedByGender();
        $beneficiariesReachedByCaste = $this->getBeneficiariesReachedByCaste();
        // Groups Reached  or dashbord fourth section
        $getGroupsReachedBySectorAndSubSector= $this->getGroupsReachedBySectorAndSubSector();


        // create Charts
        $charts = $this->createCharts(
            //dashboard section 1
            $totalGroups, $targetGroups, $achievedGroups,  $getGroupsBySectorAndSubSector,
            //dashboard section 2
            $groupMembersBySectorGender,$groupMembersBySubSectorGender,$groupMembershipByGender, $groupMembershipByCaste,
            // dashboard section 3
            $beneficiariesReachedBySectorGender,$beneficiariesReachedBySubSectorGender,$beneficiariesReachedByGender,$beneficiariesReachedByCaste, 
            // dashboard section 4
           $getGroupsReachedBySectorAndSubSector,

        );



        return [
            'totalGroups' => $totalGroups,
            'timeElapsedPercentage' => $timeElapsedPercentage,
            'expenditurePercentage' => $expenditurePercentage,
            'disbursementPercentage' => $disbursementPercentage,
            'elapsedDays' => $elapsedDays,
            'totalDays' => $totalDays,
            'progress' => $progress,
            'expenditureDisplay' => $expenditureDisplay,
            'disbursementDisplay' => $disbursementDisplay,
            'charts' => $charts,
            'rawData' => [
                'totalBeneficiariesEstimated' => $totalBeneficiariesEstimated,
                'totalBeneficiariesReached' => $totalBeneficiariesReached
            ],
        ];
    }

    protected function createCharts(
        $totalGroups,$targetGroups, $achievedGroups,  $getGroupsBySectorAndSubSector,
        $groupMembersBySectorGender,$groupMembersBySubSectorGender,$groupMembershipByGender, $groupMembershipByCaste,
        $beneficiariesReachedBySectorGender,$beneficiariesReachedBySubSectorGender,$beneficiariesReachedByGender,$beneficiariesReachedByCaste, 
        $getGroupsReachedBySectorAndSubSector,
    ) {
        $charts = [];

        // Delegate to category-specific chart creation methods
        $charts = array_merge($charts, $this->createGroupCharts(
            $totalGroups,$targetGroups, $achievedGroups,  $getGroupsBySectorAndSubSector
        ));

        $charts = array_merge($charts, $this->createGroupMemberCharts(
            $groupMembersBySectorGender,$groupMembersBySubSectorGender,$groupMembershipByGender, $groupMembershipByCaste,
        ));

        $charts = array_merge($charts, $this->createBeneficiaryReachedCharts(
            $beneficiariesReachedBySectorGender,$beneficiariesReachedBySubSectorGender,$beneficiariesReachedByGender,$beneficiariesReachedByCaste, 
        ));

        $charts = array_merge($charts, $this->createGroupsReachedCharts(
            $getGroupsReachedBySectorAndSubSector,
        ));

        return $charts;
    }



    // Helper methods for queries (extracted for clarity and reusability)

    protected function createGroupCharts(
        $totalGroups, $targetGroups, $achievedGroups, $getGroupsBySectorAndSubSector
    ) {
        $charts = [];
    
        // Total Groups Chart
        $charts['totalGroupsChart'] = new Chart;
        $charts['totalGroupsChart']->labels(['Total']);
        $charts['totalGroupsChart']->dataset('Total Groups', 'bar', [(int)$totalGroups])
            ->options(['backgroundColor' => '#71dd37']);
    
        // Target vs Achieved Chart
        $charts['groupsTargetVsAchievedChart'] = new Chart;
        $charts['groupsTargetVsAchievedChart']->labels(['Target', 'Achieved']);
        $charts['groupsTargetVsAchievedChart']->dataset('Groups', 'bar', [(int)$targetGroups, (int)$achievedGroups])
            ->options(['backgroundColor' => ['#696cff', '#ff3e1d']]);
    
        // Sector and Subsector Chart
        $charts['groupBySectorSubsectorChart'] = new Chart;

        // Validate input data
        if (!is_array($getGroupsBySectorAndSubSector) || empty($getGroupsBySectorAndSubSector)) {
            $charts['groupBySectorSubsectorChart']->labels(['No Data']);
            $charts['groupBySectorSubsectorChart']->dataset('No Data', 'bar', [0])
                ->options(['backgroundColor' => '#ff3e1d']);
            $charts['groupBySectorSubsectorChart']->options([
                'plugins' => [
                    'legend' => ['display' => false],
                    'title' => ['display' => true, 'text' => 'No Sector/Subsector Data Available']
                ]
            ]);
        } else {
            // Prepare labels (just sectors) and collect all subsectors
            $sectors = array_keys($getGroupsBySectorAndSubSector);
            $allSubSectors = [];
            foreach ($getGroupsBySectorAndSubSector as $sector => $subSectors) {
                if (is_array($subSectors)) {
                    $allSubSectors = array_merge($allSubSectors, array_keys($subSectors));
                }
            }
            $uniqueSubSectors = array_unique($allSubSectors);
        
            // Set labels to just the sector names
            $charts['groupBySectorSubsectorChart']->labels($sectors);
        
            // Prepare datasets for stacking
            $datasetMap = [];
            foreach ($uniqueSubSectors as $subSector) {
                $counts = [];
                foreach ($sectors as $sector) {
                    $subSectors = isset($getGroupsBySectorAndSubSector[$sector]) && is_array($getGroupsBySectorAndSubSector[$sector])
                        ? $getGroupsBySectorAndSubSector[$sector]
                        : ['No Subsector' => 0];
                    $counts[] = (int)($subSectors[$subSector] ?? 0);
                }
                $datasetMap[$subSector] = $counts;
            }
        
            // Colors for subsectors
            $colors = ['#696cff', '#ff3e1d', '#71dd37', '#03c3ec', '#ffab00'];
            $colorIndex = 0;
        
            // Add datasets for each subsector
            foreach ($uniqueSubSectors as $subSector) {
                $charts['groupBySectorSubsectorChart']->dataset((string)$subSector, 'bar', $datasetMap[$subSector])
                    ->options([
                        'backgroundColor' => $colors[$colorIndex % count($colors)]
                    ]);
                $colorIndex++;
            }
        
        }
        return $charts;
    }
    /**
     * Charts related to Group Members
     */
    protected function createGroupMemberCharts(
        $groupMembersBySectorGender,$groupMembersBySubSectorGender, $groupMembershipByGender, $groupMembershipByCaste,
    ) {

        $charts = [];

        // dd($groupMembersBySubSectorGender);

        // Group Members by Sector and Gender Chart
        $charts['groupMembersBySectorGenderChart'] = new Chart;
        $sectors = array_keys($groupMembersBySectorGender);
        $charts['groupMembersBySectorGenderChart']->labels($sectors);
        $maleData = array_map(fn($genders) => $genders['Male'] ?? 0, $groupMembersBySectorGender);
        $femaleData = array_map(fn($genders) => $genders['Female'] ?? 0, $groupMembersBySectorGender);
        $charts['groupMembersBySectorGenderChart']->dataset('Male', 'bar', $maleData)
            ->options(['backgroundColor' => $this->colorPalette[0]]); // e.g., Blue for Male
        $charts['groupMembersBySectorGenderChart']->dataset('Female', 'bar', $femaleData)
            ->options(['backgroundColor' => $this->colorPalette[1 % count($this->colorPalette)]]); // e.g., Pink for Female
        $charts['groupMembersBySectorGenderChart']->options([
            'scales' => [
                'y' => [
                    'beginAtZero' => true, // Start Y-axis at 0
                ],
                'x' => [
                    'barPercentage' => 0.4, // Width of each bar
                    'categoryPercentage' => 0.8, // Space between sector groups
                ],
            ],
            'legend' => [
                'display' => true, // Show legend (Male, Female)
            ],
        ]);


        // Group Members by SubSector and Gender Chart
        $charts['groupMembersBySubSectorGenderChart'] = new Chart;
        $subsectors = array_keys($groupMembersBySubSectorGender); 
        $charts['groupMembersBySubSectorGenderChart']->labels($subsectors);
        // Extract Male and Female data for all subsectors
        $maleData = array_map(fn($genders) => $genders['Male'] ?? 0, $groupMembersBySubSectorGender);
        $femaleData = array_map(fn($genders) => $genders['Female'] ?? 0, $groupMembersBySubSectorGender);
        // Define Male dataset
        $charts['groupMembersBySubSectorGenderChart']->dataset('Male', 'bar', $maleData)
            ->options(['backgroundColor' => $this->colorPalette[0]]); // e.g., Blue for Male
        // Define Female dataset
        $charts['groupMembersBySubSectorGenderChart']->dataset('Female', 'bar', $femaleData)
            ->options(['backgroundColor' => $this->colorPalette[1 % count($this->colorPalette)]]); // e.g., Pink for Female
        // Customize chart options for side-by-side bars and separation
        $charts['groupMembersBySubSectorGenderChart']->options([
            'scales' => [
                'y' => [
                    'beginAtZero' => true, // Start Y-axis at 0
                ],
                'x' => [
                    'barPercentage' => 0.4, // Width of each bar
                    'categoryPercentage' => 0.8, // Space between subsector groups
                ],
            ],
            'legend' => [
                'display' => true, // Show legend (Male, Female)
            ],
        ]);

    
         $charts['groupMembershipByGenderChart'] = new Chart;
         $charts['groupMembershipByGenderChart']->labels(array_keys($groupMembershipByGender));
         $charts['groupMembershipByGenderChart']->dataset('Membership by Gender', 'pie', array_values($groupMembershipByGender));
         $charts['groupMembershipByGenderChart']->options(['backgroundColor' => $this->colorPalette]);
    
         $charts['groupMembershipByCasteChart'] = new Chart;
         $charts['groupMembershipByCasteChart']->labels(array_keys($groupMembershipByCaste));
         $charts['groupMembershipByCasteChart']->dataset('Membership by Caste', 'bar', array_values($groupMembershipByCaste));
         $charts['groupMembershipByCasteChart']->options([
             'backgroundColor' => [
                 '#696cff', // Color for first caste
                 '#ff3e1d', // Color for second caste
                 '#71dd37', // Color for third caste
                 '#ffab00', // Color for fourth caste
                 '#03c3ec', // Color for fifth caste
                 // Add more colors as needed based on the number of castes
             ]
         ]);
    
        return $charts;
    }
    protected function createBeneficiaryReachedCharts(
        $beneficiariesReachedBySectorGender,$beneficiariesReachedBySubSectorGender, $beneficiariesReachedByGender, $beneficiariesReachedByCaste,
    ) {

        $charts = [];

        // Beneficiaries Reached by Sector and Gender Chart
        $charts['beneficiariesReachedBySectorGenderChart'] = new Chart;
        $sectors = array_keys($beneficiariesReachedBySectorGender);
        $charts['beneficiariesReachedBySectorGenderChart']->labels($sectors);
        $maleData = array_map(fn($genders) => $genders['Male'] ?? 0, $beneficiariesReachedBySectorGender);
        $femaleData = array_map(fn($genders) => $genders['Female'] ?? 0, $beneficiariesReachedBySectorGender);
        $charts['beneficiariesReachedBySectorGenderChart']->dataset('Male', 'bar', $maleData)
            ->options(['backgroundColor' => $this->colorPalette[0]]); // e.g., Blue for Male
        $charts['beneficiariesReachedBySectorGenderChart']->dataset('Female', 'bar', $femaleData)
            ->options(['backgroundColor' => $this->colorPalette[1 % count($this->colorPalette)]]); // e.g., Pink for Female
        $charts['beneficiariesReachedBySectorGenderChart']->options([
            'scales' => [
                'y' => [
                    'beginAtZero' => true, // Start Y-axis at 0
                ],
                'x' => [
                    'barPercentage' => 0.4, // Width of each bar
                    'categoryPercentage' => 0.8, // Space between sector groups
                ],
            ],
            'legend' => [
                'display' => true, // Show legend (Male, Female)
            ],
        ]);


        // Beneficiaries Reached by SubSector and Gender Chart
        $charts['beneficiariesReachedBySubSectorGenderChart'] = new Chart;
        $subsectors = array_keys($beneficiariesReachedBySubSectorGender); 
        $charts['beneficiariesReachedBySubSectorGenderChart']->labels($subsectors);
        // Extract Male and Female data for all subsectors
        $maleData = array_map(fn($genders) => $genders['Male'] ?? 0, $beneficiariesReachedBySubSectorGender);
        $femaleData = array_map(fn($genders) => $genders['Female'] ?? 0, $beneficiariesReachedBySubSectorGender);
        // Define Male dataset
        $charts['beneficiariesReachedBySubSectorGenderChart']->dataset('Male', 'bar', $maleData)
            ->options(['backgroundColor' => $this->colorPalette[0]]); // e.g., Blue for Male
        // Define Female dataset
        $charts['beneficiariesReachedBySubSectorGenderChart']->dataset('Female', 'bar', $femaleData)
            ->options(['backgroundColor' => $this->colorPalette[1 % count($this->colorPalette)]]); // e.g., Pink for Female
        // Customize chart options for side-by-side bars and separation
        $charts['beneficiariesReachedBySubSectorGenderChart']->options([
            'scales' => [
                'y' => [
                    'beginAtZero' => true, // Start Y-axis at 0
                ],
                'x' => [
                    'barPercentage' => 0.4, // Width of each bar
                    'categoryPercentage' => 0.8, // Space between subsector groups
                ],
            ],
            'legend' => [
                'display' => true, // Show legend (Male, Female)
            ],
        ]);

    
         $charts['beneficiariesReachedByGenderChart'] = new Chart;
         $charts['beneficiariesReachedByGenderChart']->labels(array_keys($beneficiariesReachedByGender));
         $charts['beneficiariesReachedByGenderChart']->dataset('Membership by Gender', 'pie', array_values($beneficiariesReachedByGender));
         $charts['beneficiariesReachedByGenderChart']->options(['backgroundColor' => $this->colorPalette]);
    
         $charts['beneficiariesReachedByCasteChart'] = new Chart;
         $charts['beneficiariesReachedByCasteChart']->labels(array_keys($beneficiariesReachedByCaste));
         $charts['beneficiariesReachedByCasteChart']->dataset('Membership by Caste', 'bar', array_values($beneficiariesReachedByCaste));
         $charts['beneficiariesReachedByCasteChart']->options([
             'backgroundColor' => [
                 '#696cff', // Color for first caste
                 '#ff3e1d', // Color for second caste
                 '#71dd37', // Color for third caste
                 '#ffab00', // Color for fourth caste
                 '#03c3ec', // Color for fifth caste
                 // Add more colors as needed based on the number of castes
             ]
         ]);
    
        return $charts;
    }

    /**
     * Charts related to Groups Reached
     */
    protected function createGroupsReachedCharts(
        $groupsReachedBySectorAndSubSector
    ) {
        $charts = [];

        // Sector and Subsector Chart
        $charts['groupsReachedBySectorAndSubSectorChart'] = new Chart;

        // Validate input data
        if (!is_array($groupsReachedBySectorAndSubSector) || empty($groupsReachedBySectorAndSubSector)) {
            $charts['groupBySectorSubsectorChart']->labels(['No Data']);
            $charts['groupBySectorSubsectorChart']->dataset('No Data', 'bar', [0])
                ->options(['backgroundColor' => '#ff3e1d']);
            $charts['groupBySectorSubsectorChart']->options([
                'plugins' => [
                    'legend' => ['display' => false],
                    'title' => ['display' => true, 'text' => 'No Sector/Subsector Data Available']
                ]
            ]);
        } else {
            // Prepare labels (just sectors) and collect all subsectors
            $sectors = array_keys($groupsReachedBySectorAndSubSector);
            $allSubSectors = [];
            foreach ($groupsReachedBySectorAndSubSector as $sector => $subSectors) {
                if (is_array($subSectors)) {
                    $allSubSectors = array_merge($allSubSectors, array_keys($subSectors));
                }
            }
            $uniqueSubSectors = array_unique($allSubSectors);
        
            // Set labels to just the sector names
            $charts['groupsReachedBySectorAndSubSectorChart']->labels($sectors);
        
            // Prepare datasets for stacking
            $datasetMap = [];
            foreach ($uniqueSubSectors as $subSector) {
                $counts = [];
                foreach ($sectors as $sector) {
                    $subSectors = isset($groupsReachedBySectorAndSubSector[$sector]) && is_array($groupsReachedBySectorAndSubSector[$sector])
                        ? $groupsReachedBySectorAndSubSector[$sector]
                        : ['No Subsector' => 0];
                    $counts[] = (int)($subSectors[$subSector] ?? 0);
                }
                $datasetMap[$subSector] = $counts;
            }
        
            // Colors for subsectors
            $colors = ['#696cff', '#ff3e1d', '#71dd37', '#03c3ec', '#ffab00'];
            $colorIndex = 0;
        
            // Add datasets for each subsector
            foreach ($uniqueSubSectors as $subSector) {
                $charts['groupsReachedBySectorAndSubSectorChart']->dataset((string)$subSector, 'bar', $datasetMap[$subSector])
                    ->options([
                        'backgroundColor' => $colors[$colorIndex % count($colors)]
                    ]);
                $colorIndex++;
            }
        
        }
        return $charts;
    }

   //groups

    protected function getGroupsBySectorAndSubSector()
    {
        $query = Group::selectRaw('
                sectors.name as sector_name,
                COALESCE(sub_sectors.name, "No Subsector") as sub_sector_name,
                COUNT(*) as count
            ')
            ->join('sectors', 'groups.sector_id', '=', 'sectors.id')
            ->leftJoin('sub_sectors', 'groups.sub_sector_id', '=', 'sub_sectors.id')
            ->groupBy('sectors.name', 'sub_sectors.name')
            ->get();

        $result = [];
        foreach ($query as $row) {
            $result[$row->sector_name][$row->sub_sector_name] = (int)$row->count; // Ensure count is integer
        }

        return $result;
    }

    // group members
    protected function getGroupMembersBySectorGender()
    {
        // Get the grouped data by sector and gender
        $groupedData = GroupMember::selectRaw('sectors.name as sector_name, mst_genders.name as gender_name, COUNT(*) as count')
            ->join('groups', 'group_members.group_id', '=', 'groups.id')
            ->join('sectors', 'groups.sector_id', '=', 'sectors.id')
            ->join('mst_genders', 'group_members.gender_id', '=', 'mst_genders.id')
            ->groupBy('sectors.id', 'sectors.name', 'mst_genders.name')
            ->get()
            ->groupBy('sector_name')
            ->map(function ($items) {
                return $items->pluck('count', 'gender_name')->toArray();
            })->toArray();

        // Calculate totals
        $totalOverall = 0;
        $totalMale = 0;
        $totalFemale = 0;

        foreach ($groupedData as $sector => $genders) {
            foreach ($genders as $gender => $count) {
                $totalOverall += $count;
                if (strtolower($gender) === 'male') {
                    $totalMale += $count;
                } elseif (strtolower($gender) === 'female') {
                    $totalFemale += $count;
                }
            }
        }

        // Add a "Totals" entry to the result
        $groupedData['Totals'] = [
            'Male' => $totalMale,
            'Female' => $totalFemale,
            'Overall' => $totalOverall
        ];

        return $groupedData;
    }


    protected function getGroupMembersBySubSectorGender(){
        $groupedData = GroupMember::selectRaw('sub_sectors.name as sub_sector_name, mst_genders.name as gender_name, COUNT(*) as count')
        ->join('groups', 'group_members.group_id', '=', 'groups.id')
        ->join('sub_sectors', 'groups.sub_sector_id', '=', 'sub_sectors.id')
        ->join('mst_genders', 'group_members.gender_id', '=', 'mst_genders.id')
        ->groupBy('sub_sectors.id', 'sub_sectors.name', 'mst_genders.name')
        ->get()
        ->groupBy('sub_sector_name')
        ->map(function ($items) {
            return $items->pluck('count', 'gender_name')->toArray();
        })->toArray();

        // Calculate totals
        $totalOverall = 0;
        $totalMale = 0;
        $totalFemale = 0;

        foreach ($groupedData as $sector => $genders) {
            foreach ($genders as $gender => $count) {
                $totalOverall += $count;
                if (strtolower($gender) === 'male') {
                    $totalMale += $count;
                } elseif (strtolower($gender) === 'female') {
                    $totalFemale += $count;
                }
            }
        }

        // Add a "Totals" entry to the result
        $groupedData['Totals'] = [
            'Male' => $totalMale,
            'Female' => $totalFemale,
            'Overall' => $totalOverall
        ];

        return $groupedData;
    }
    protected function getGroupMembershipByGender()
    {
        return GroupMember::selectRaw('mst_genders.name as gender_name, COUNT(*) as count')
            ->join('mst_genders', 'group_members.gender_id', '=', 'mst_genders.id')
            ->groupBy('gender_id', 'mst_genders.name')
            ->pluck('count', 'gender_name')
            ->toArray();
    }
    protected function getGroupMembershipByCaste()
    {
        return GroupMember::selectRaw('castes.name as caste_name, COUNT(*) as count')
        ->join('castes', 'group_members.caste_id', '=', 'castes.id')
        ->groupBy('caste_id', 'castes.name')
        ->pluck('count', 'caste_name')
        ->toArray();
    }
    // beneficiariesReached
    protected function getBeneficiariesBySectorGender()
    {
        // Get the grouped data by sector and gender
        $groupedData = GroupMember::selectRaw('sectors.name as sector_name, mst_genders.name as gender_name, COUNT(*) as count')
            ->join('groups', 'group_members.group_id', '=', 'groups.id')
            ->join('sectors', 'groups.sector_id', '=', 'sectors.id')
            ->join('mst_genders', 'group_members.gender_id', '=', 'mst_genders.id')
            ->groupBy('sectors.id', 'sectors.name', 'mst_genders.name')
            ->get()
            ->groupBy('sector_name')
            ->map(function ($items) {
                return $items->pluck('count', 'gender_name')->toArray();
            })->toArray();

        // Calculate totals
        $totalOverall = 0;
        $totalMale = 0;
        $totalFemale = 0;

        foreach ($groupedData as $sector => $genders) {
            foreach ($genders as $gender => $count) {
                $totalOverall += $count;
                if (strtolower($gender) === 'male') {
                    $totalMale += $count;
                } elseif (strtolower($gender) === 'female') {
                    $totalFemale += $count;
                }
            }
        }

        // Add a "Totals" entry to the result
        $groupedData['Totals'] = [
            'Male' => $totalMale,
            'Female' => $totalFemale,
            'Overall' => $totalOverall
        ];

        return $groupedData;
    }
    protected function getBeneficiariesBySubSectorGender(){
        $groupedData = GroupMember::selectRaw('sub_sectors.name as sub_sector_name, mst_genders.name as gender_name, COUNT(*) as count')
        ->join('groups', 'group_members.group_id', '=', 'groups.id')
        ->join('sub_sectors', 'groups.sub_sector_id', '=', 'sub_sectors.id')
        ->join('mst_genders', 'group_members.gender_id', '=', 'mst_genders.id')
        ->groupBy('sub_sectors.id', 'sub_sectors.name', 'mst_genders.name')
        ->get()
        ->groupBy('sub_sector_name')
        ->map(function ($items) {
            return $items->pluck('count', 'gender_name')->toArray();
        })->toArray();

        // Calculate totals
        $totalOverall = 0;
        $totalMale = 0;
        $totalFemale = 0;

        foreach ($groupedData as $sector => $genders) {
            foreach ($genders as $gender => $count) {
                $totalOverall += $count;
                if (strtolower($gender) === 'male') {
                    $totalMale += $count;
                } elseif (strtolower($gender) === 'female') {
                    $totalFemale += $count;
                }
            }
        }

        // Add a "Totals" entry to the result
        $groupedData['Totals'] = [
            'Male' => $totalMale,
            'Female' => $totalFemale,
            'Overall' => $totalOverall
        ];

        return $groupedData;
    }
    protected function getBeneficiariesReachedByGender()
    {
        return GroupMember::selectRaw('mst_genders.name as gender_name, COUNT(*) as count')
            ->join('mst_genders', 'group_members.gender_id', '=', 'mst_genders.id')
            ->groupBy('gender_id', 'mst_genders.name')
            ->pluck('count', 'gender_name')
            ->toArray();
    }
    protected function getBeneficiariesReachedByCaste()
    {
        return GroupMember::selectRaw('castes.name as caste_name, COUNT(*) as count')
        ->join('castes', 'group_members.caste_id', '=', 'castes.id')
        ->groupBy('caste_id', 'castes.name')
        ->pluck('count', 'caste_name')
        ->toArray();
    }

    //groups reached
    protected function getGroupsReachedBySectorAndSubSector()
    {
        $query = Group::selectRaw('
                sectors.name as sector_name,
                COALESCE(sub_sectors.name, "No Subsector") as sub_sector_name,
                COUNT(*) as count
            ')
            ->join('sectors', 'groups.sector_id', '=', 'sectors.id')
            ->leftJoin('sub_sectors', 'groups.sub_sector_id', '=', 'sub_sectors.id')
            ->groupBy('sectors.name', 'sub_sectors.name')
            ->get();

        $result = [];
        foreach ($query as $row) {
            $result[$row->sector_name][$row->sub_sector_name] = (int)$row->count; // Ensure count is integer
        }

        return $result;
    }
 
}