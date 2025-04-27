<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

  
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div style="margin-bottom: 20px;">
            <label for="update_password_current_password" style="display: block; font-weight: 500; color: #374151; margin-bottom: 6px;">
                {{ __('Current Password') }}
            </label>
            <input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                autocomplete="current-password"
                style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;"
            >
            @error('updatePassword.current_password')
                <div style="color: red; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="update_password_password" style="display: block; font-weight: 500; color: #374151; margin-bottom: 6px;">
                {{ __('New Password') }}
            </label>
            <input 
                id="update_password_password" 
                name="password" 
                type="password" 
                autocomplete="new-password"
                style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;"
            >
            @error('updatePassword.password')
                <div style="color: red; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="update_password_password_confirmation" style="display: block; font-weight: 500; color: #374151; margin-bottom: 6px;">
                {{ __('Confirm Password') }}
            </label>
            <input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                autocomplete="new-password"
                style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;"
            >
            @error('updatePassword.password_confirmation')
                <div style="color: red; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; align-items: center; gap: 16px; margin-top: 24px;">
            <button 
                type="submit"
                style="background-color: #1f2937; color: white; padding: 8px 16px; font-size: 14px; border-radius: 6px; border: none; cursor: pointer;"
            >
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
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