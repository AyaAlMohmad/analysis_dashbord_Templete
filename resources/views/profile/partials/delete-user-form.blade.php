<section style="margin-top: 60px; margin-bottom: 40px;">
    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 8px;">
        {{ __('Delete Account') }}
    </h2>

    <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

   
    <button
        id="openDeleteModal"
        style="background-color: #dc2626; color: white; padding: 8px 16px; font-size: 14px; border-radius: 6px; border: none; cursor: pointer;"
    >
        {{ __('Delete Account')}}
    </button>

    <div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
        <div style="background: white; padding: 30px; border-radius: 8px; max-width: 500px; margin: 100px auto;">
            <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 8px;">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div style="margin-bottom: 20px;">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="{{ __('Password') }}"
                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;"
                    >
                    @error('userDeletion.password')
                        <div style="color: red; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button
                        type="button"
                        id="cancelDeleteModal"
                        style="background-color: #6b7280; color: white; padding: 8px 16px; font-size: 14px; border-radius: 6px; border: none; cursor: pointer;"
                    >
                        {{ __('Cancel') }}
                    </button>

                    <button
                        type="submit"
                        style="background-color: #dc2626; color: white; padding: 8px 16px; font-size: 14px; border-radius: 6px; border: none; cursor: pointer;"
                    >
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
   
    document.getElementById('openDeleteModal').addEventListener('click', function() {
        document.getElementById('deleteModal').style.display = 'block';
    });

   
    document.getElementById('cancelDeleteModal').addEventListener('click', function() {
        document.getElementById('deleteModal').style.display = 'none';
    });
</script>
