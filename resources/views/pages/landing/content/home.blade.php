<div class="slider mb-5">
    <div style="height: 40vh;">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('assets/img/illustrations/login.jpg') }}" class="d-block w-100 h-100"
                        alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Food and Nutrition Security Enhancement Project (FANSEP) </h5>
                        <p>is funded by Global Agriculture and Food Security Program (GAFSP).</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/img/illustrations/banner1.jpg') }}" class="d-block w-100 h-100"
                        alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Food and Nutrition Security Enhancement Project (FANSEP) </h5>
                        <p>is funded by Global Agriculture and Food Security Program (GAFSP).</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/img/illustrations/login.png') }}" class="d-block w-100 h-100"
                        alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Food and Nutrition Security Enhancement Project (FANSEP) </h5>
                        <p>is funded by Global Agriculture and Food Security Program (GAFSP).</p>
                    </div>

                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>
<div class="row justify-content-between mb-5">
    <div class="col-md-6 pe-5">
        <div class="my-4">
            <div class="intro">who we are</div>
        </div>
        <p class="pe-5 mb-0">Food and Nutrition Security Enhancement Project (FANSEP) is funded by Global Agriculture
            and Food Security Program (GAFSP). The grant agreement between Government of Nepal (GON) and International
            Development Association (IDA) for the FANSEP was signed on 1st December 2023. The total Project cost is 22
            million USD of which 20 million USD is GAFSP grant and 2 million USD is counterpart funding from GON. This
            project is implemented by Ministry of Agriculture and Livestock Development (MoALD), supervised by World
            Bank (WB) and Technical Assistance is provided by Food and Agriculture Organizations (FAO) of the United
            Nations. This project is implemented in 16 Rural Municipalities (RMs) of eight districts (Gorkha, Dhading,
            Sindhupalchowk, Dolakha, Dhanusha, Mahottari, Siraha and Saptari) for the duration of 3.5 years. The project
            aims to reach 55,000 direct beneficiaries.</p>
    </div>
    <div class="col-md-6">
        <div class="my-4">
            <div class="intro">Meet our key officals</div>
        </div>
        <div class="row officials-container justify-content-center">
            @php
                $officials = [
                    ['name' => 'Dr. Arun Kafle', 'title' => 'Project Director', 'img' => 'project_director.png'],
                    [
                        'name' => 'Dr. Tapendra Bahadur Shah',
                        'title' => 'Information Officer',
                        'img' => 'information_officer.jpg',
                    ],
                    ['name' => 'Deepak Poudel', 'title' => 'Nodal Officer', 'img' => 'nodal_officer.jpg'],
                ];
            @endphp
            @foreach ($officials as $official)
                <div class="col-md-4 official-item position-relative">
                    <img src="{{ asset('img/officials/' . $official['img']) }}" alt="{{ $official['name'] }}"
                        class="rounded me-3 official-img w-100">
                    <div class="text-start position-absolute bottom-0 bg-white w-80 mx-4 px-3 py-1 mb-2 rounded-2">
                        <h6 class="mb-0">{{ $official['name'] }}</h6>
                        <p class="text-muted small mb-0">{{ $official['title'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-6 col-md-3 mb-5">
        <div class="card border">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="fw-medium d-block mb-1">Total Users</span>
                        <div class="d-flex align-items-center mt-1">
                            <h4 class="mb-0 me-2">28.5K</h4>
                            <span class="badge bg-label-success">+12%</span>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-user bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3 mb-5">
        <div class="card border">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="fw-medium d-block mb-1">Active Projects</span>
                        <div class="d-flex align-items-center mt-1">
                            <h4 class="mb-0 me-2">45.6K</h4>
                            <span class="badge bg-label-danger">-8%</span>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-briefcase bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3 mb-5">
        <div class="card border">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="fw-medium d-block mb-1">Active Projects</span>
                        <div class="d-flex align-items-center mt-1">
                            <h4 class="mb-0 me-2">45.6K</h4>
                            <span class="badge bg-label-danger">-8%</span>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-briefcase bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3 mb-5">
        <div class="card border">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="fw-medium d-block mb-1">Active Projects</span>
                        <div class="d-flex align-items-center mt-1">
                            <h4 class="mb-0 me-2">45.6K</h4>
                            <span class="badge bg-label-danger">-8%</span>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-briefcase bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Main Content Section -->
<div class="row">
    <div class="col-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                Monthly Statistics
                <div class="dropdown">
                    <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                        <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                        <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="barChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card h-100">
            <div class="card-header">
                Distribution
            </div>
            <div class="card-body">
                <canvas id="pieChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                Projects Implemented District
            </div>
            <div class="card-body">
                <img src="{{ asset('img/projects/projected_implemented_district.jpg') }}" alt="Nepal Map"
                    class="img-fluid rounded" style="width: 100%; height: auto;">
            </div>
        </div>
    </div>


</div>
@php
    $tabs = [
        [
            'id' => 'notices',
            'title' => 'Notices',
            'icon' => 'bi-bell',
            'content' => [
                ['title' => 'Notice 1 - Sed do eiusmod tempor.', 'file' => 'notice1.pdf'],
                ['title' => 'Notice 2 - Ut labore et dolore magna.', 'file' => 'notice2.pdf'],
            ],
        ],
        [
            'id' => 'news',
            'title' => 'News',
            'icon' => 'bi-newspaper',
            'content' => [
                ['title' => 'News 1 - Lorem ipsum dolor sit amet.', 'file' => 'news1.pdf'],
                ['title' => 'News 2 - Consectetur adipiscing elit.', 'file' => 'news2.pdf'],
            ],
        ],
        [
            'id' => 'invitation',
            'title' => 'Invitation for Bids',
            'icon' => 'bi-envelope',
            'content' => [['title' => 'Bid 1 - Sed do eiusmod tempor.', 'file' => 'bid1.pdf']],
        ],
        [
            'id' => 'press',
            'title' => 'Press Release',
            'icon' => 'bi-megaphone',
            'content' => [['title' => 'Press 1 - Ut labore et dolore magna.', 'file' => 'press1.pdf']],
        ],
        [
            'id' => 'bills',
            'title' => 'Bills Payment',
            'icon' => 'bi-receipt',
            'content' => [['title' => 'Bill 1 - Sed do eiusmod tempor.', 'file' => 'bill1.pdf']],
        ],
        [
            'id' => 'letter',
            'title' => 'Letter',
            'icon' => 'bi-mailbox',
            'content' => [['title' => 'Letter 1 - Ut labore et dolore magna.', 'file' => 'letter1.pdf']],
        ],
    ];
@endphp



<!-- Row 3: Rules, Publications, FAQ -->
<div class="row g-4 mt-4">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                Rules and Regulations
            </div>
            <div class="card-body">
                <p>Content for rules and regulations goes here.</p>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                Publications
            </div>
            <div class="card-body">
                <p>Content for publications goes here.</p>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                FAQ
            </div>
            <div class="card-body">
                <p>Content for FAQ goes here.</p>
            </div>
        </div>
    </div>
</div>

<!-- Row 5: Image Gallery -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Image Gallery
            </div>
            <div class="card-body position-relative p-0">
                <!-- Left Arrow -->
                <button class="btn position-absolute start-0 top-50 translate-middle-y z-1 bg-transparent border-0 p-0"
                    onclick="scrollGallery(-1)">
                    <span class="fs-1 text-muted">&larr;</span>
                </button>

                <!-- Gallery Container -->
                <div class="gallery-container d-flex overflow-hidden scroll-smooth gap-2 p-2"
                    style="scroll-behavior: smooth;">
                    @php
                        $galleryImages = ['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg'];
                    @endphp
                    @foreach ($galleryImages as $image)
                        <div class="flex-shrink-0 rounded" style="width: calc((100% - 1rem) / 6);">
                            <div class="card h-100">
                                <img src="{{ asset('img/gallery/' . $image) }}" alt="Gallery Image"
                                    class="card-img-top img-fluid rounded-top"
                                    style="height: 120px; object-fit: cover;">
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right Arrow -->
                <button class="btn position-absolute end-0 top-50 translate-middle-y z-1 bg-transparent border-0 p-0"
                    onclick="scrollGallery(1)">
                    <span class="fs-1 text-muted">&rarr;</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--  Country-wide Details -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                देश भरको विवरण
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap">
                    <div class="mb-2 me-3 flex-fill" style="flex-basis: 23%;">आयोजना क्लष्‍टर इकाई, सप्तरी</div>
                    <div class="mb-2 me-3 flex-fill" style="flex-basis: 23%;">आयोजना क्लष्‍टर इकाई, धनुषा</div>
                    <div class="mb-2 me-3 flex-fill" style="flex-basis: 23%;">आयोजना क्लष्‍टर इकाई, सिन्धुपाल्चोक
                    </div>
                    <div class="mb-2 me-3 flex-fill" style="flex-basis: 23%;">Food and Nutrition Security Enhancement
                        Project</div>
                    <div class="mb-2 me-3 flex-fill" style="flex-basis: 23%;">आयोजना क्लष्‍टर इकाई, गोरखा</div>
                </div>
            </div>
        </div>
    </div>
</div>

