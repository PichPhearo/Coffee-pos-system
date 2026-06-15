@if(auth()->user()->role === 'admin')
@include('admin.profile.edit')
@elseif(auth()->user()->role === 'barista')
@include('kitchen.profile')
@elseif(auth()->user()->role === 'cashier')
<x-layouts.pos title="Profile" subtitle="Manage your account settings">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-brown-100">
            <h2 class="text-lg font-serif text-espresso mb-4">Update Profile Information</h2>
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-brown-100">
            <h2 class="text-lg font-serif text-espresso mb-4">Update Password</h2>
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-brown-100">
            <h2 class="text-lg font-serif text-espresso mb-4">Delete Account</h2>
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-layouts.pos>
@else
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@endif