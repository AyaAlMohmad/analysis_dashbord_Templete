<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div style="margin-bottom: 20px;">
            <label for="name" style="display: block; font-weight: 500; color: #374151; margin-bottom: 6px;">
                {{ __('Name') }}
            </label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                value="{{ old('name', $user->name) }}" 
                required 
                autofocus 
                autocomplete="name"
                style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;"
            >
            @error('name')
                <div style="color: red; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="email" style="display: block; font-weight: 500; color: #374151; margin-bottom: 6px;">
                {{ __('Email') }}
            </label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                value="{{ old('email', $user->email) }}" 
                required 
                autocomplete="username"
                style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;"
            >
            @error('email')
                <div style="color: red; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top: 10px;">
                    <p style="font-size: 14px; color: #374151;">
                        {{ __('Your email address is unverified.') }}

                        <button 
                            form="send-verification" 
                            type="submit"
                            style="font-size: 14px; text-decoration: underline; color: #4b5563; cursor: pointer; border: none; background: none; padding: 0;"
                        >
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p style="margin-top: 8px; font-weight: 500; font-size: 14px; color: #059669;">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div style="display: flex; align-items: center; gap: 16px; margin-top: 24px;">
            <button 
                type="submit"
                style="background-color: #1f2937; color: white; padding: 8px 16px; font-size: 14px; border-radius: 6px; border: none; cursor: pointer;"
            >
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p 
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    style="font-size: 14px; color: #6b7280;"
                >
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
