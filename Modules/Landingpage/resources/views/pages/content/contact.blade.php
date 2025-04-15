<div data-bs-spy="scroll" class="scrollspy-example" style="padding-top: 8rem">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container-xl py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Contact Information -->
                            <div class="col-lg-6">
                                <h5 class="fw-bold mb-3">{{ __('landing.Get in Touch') }}</h5>
                                <p class="text-muted mb-4">
                                   {{__('landing.We’d love to hear from you! Reach out with any questions or feedback.')}}
                                </p>
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="bx bx-envelope me-2 text-primary"></i>
                                        <strong>{{ __('field.email') }}:</strong>
                                        <span>{{ $siteSettings['contact_settings']['email'] ?? 'fansep2018@gmail.com' }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <i class="bx bx-phone me-2 text-primary"></i>
                                        <span>{{ $siteSettings['contact_settings']['phone'] ?? '01-5552971/5010108' }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <i class="bx bx-map me-2 text-primary"></i>
                                        <span>{{ __('landing.' . $siteSettings['contact_settings']['address'] ?? '') }}</span>
                                    </li>
                                    <li>
                                        <i class="bx bx-time me-2 text-primary"></i>
                                        <strong>Hours:</strong> Mon - Fri, 9:00 AM - 5:00 PM
                                    </li>
                                </ul>
                                <!-- Social Links -->
                                <div class="mt-4">
                                    <a href="#" class="btn btn-icon btn-outline-primary me-2">
                                        <i class="bx bxl-facebook"></i>
                                    </a>
                                    <a href="#" class="btn btn-icon btn-outline-info me-2">
                                        <i class="bx bxl-twitter"></i>
                                    </a>
                                    <a href="#" class="btn btn-icon btn-outline-danger">
                                        <i class="bx bxl-instagram"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Contact Form -->
                            <div class="col-lg-6">
                                <h5 class="fw-bold mb-3">{{ __('landing.Send Us a Message') }}</h5>
                                <form action="{{ route('landing.contacts.submit') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <x-forms.input name="name" :label="__('group.name')" :value="old('name', $model->name ?? '')" />
                                    </div>

                                    <div class="mb-3">
                                        <x-forms.input name="email" type="email" label="{{ __('Email') }}"
                                            :value="old('email', $model->email ?? '')" required />
                                    </div>

                                    <div class="mb-3">
                                        <x-forms.input name="subject" :label="__('group.subject')" :value="old('subject', $model->subject ?? '')" />
                                    </div>

                                    <div class="mb-3">
                                        <x-forms.input-textarea name="subject" :label="__('group.subject')" :value="old('subject', $model->subject ?? '')" />
                                    </div>

                                    <button type="submit" class="btn btn-primary">Send Message</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@section('styles')
    <style>
        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .card-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 0.375rem;
            font-size: 0.95rem;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content-wrapper {
            min-height: calc(100vh - 100px);
        }

        .footer-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        .text-primary {
            color: #696cff !important;
        }

        /* Sneat’s primary color */
    </style>
@endsection
