<form id="contactForm" class="mb-3" action="{{ route('admin.contacts.update', $contact->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="row">
        <div class="col-md-6 mb-3">
            <x-forms.input name="name" :value="$contact->name"/>
        </div>
        <div class="col-md-6 mb-3">
            <x-forms.input name="email" :value="$contact->email"/>
        </div>
        <div class="col-md-12 mb-3">
            <x-forms.input name="subject" :value="$contact->email"/>
        </div>
        <div class="col-md-12 mb-3">
            <x-forms.input-textarea name="message" :value="$contact->message"/>
        </div>

        <div class="mt-2">
            <button type="submit" class="btn btn-primary me-2">{{ __('button.update')}}</button>
            <button type="reset" class="btn btn-outline-secondary">{{ __('button.reset') }}</button>
        </div>
    </div>
</form>
