<div data-bs-spy="scroll" class="scrollspy-example" style="padding-top: 8rem">
    <div class="container-xl py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4 text-primary">
                            <i class="bx bx-help-circle me-2"></i> {{ __('landing.Frequently Asked Questions') }}
                        </h5>
                        <div class="accordion" id="faqAccordion">
                            @forelse ($faqs as $index => $faq)
                                <div class="accordion-item border mb-2" style="border-radius: 0.375rem;">
                                    <h2 class="accordion-header" id="heading{{ $index }}">
                                        <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $index }}"
                                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                            aria-controls="collapse{{ $index }}">
                                            <i class="bx bx-question-mark me-2 text-primary"></i>
                                            <span class="fw-medium text-dark">{{ $faq->question }}</span>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index }}"
                                        class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                        aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body text-muted">
                                            <i class="bx bx-info-circle me-2 text-info"></i>
                                            {!! $faq->answer !!}
                                            @if ($faq->category)
                                                <p class="mt-2 fs-sm text-muted">
                                                    <i class="bx bx-folder me-1"></i>
                                                    Category: {{ $faq->category }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="bx bx-info-circle me-2"></i>
                                    No FAQs available at this time.
                                </div>
                            @endforelse
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
            border: none;
        }

        .card-body {
            padding: 2rem;
        }

        .accordion-item {
            transition: all 0.3s ease;
        }

        .accordion-item:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .accordion-button {
            font-weight: 500;
            color: #333;
            /* Darker for questions */
            background-color: #fff;
            border: none;
            padding: 1.25rem;
            border-radius: 0.375rem !important;
        }

        .accordion-button:not(.collapsed) {
            color: #696cff;
            /* Sneat primary color for active state */
            background-color: #f8f9fa;
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .accordion-body {
            font-size: 0.95rem;
            color: #666;
            /* Lighter for answers */
            padding: 1.5rem;
            background-color: #fafafa;
            /* Subtle background */
            border-top: 1px solid #e0e0e0;
        }

        .text-primary {
            color: #696cff !important;
        }

        /* Sneat primary */
        .text-info {
            color: #03c3ec !important;
        }

        /* Sneat info */
        .fs-sm {
            font-size: 0.875rem;
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
    </style>
@endsection
