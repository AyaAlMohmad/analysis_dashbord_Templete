@extends('layouts.app')

@section('content')
<h2 style="font-size: 24px; font-weight: bold; color: #4B5563; margin-bottom: 30px;">
    Profile
</h2>

<div style="padding: 50px 0; background-color: #f3f4f6; min-height: 100vh;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">

        <!-- Profile Information Section -->
        <div style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 40px;">
            <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 16px;">
                Profile Information
            </h3>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">
                Update your account's profile information and email address.
            </p>

            <div style="max-width: 600px;">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password Section -->
        <div style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 40px;">
            <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 16px;">
                Update Password
            </h3>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">
                Ensure your account is using a long, random password to stay secure.
            </p>

            <div style="max-width: 600px;">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account Section -->
        <div style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px;">
            <h3 style="font-size: 18px; font-weight: 600; color: #b91c1c; margin-bottom: 16px;">
                Delete Account
            </h3>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">
                Permanently delete your account. This action cannot be undone.
            </p>

            <div style="max-width: 600px;">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>

@endsection
