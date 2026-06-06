@extends('layouts.app')

@section('title', 'Dashboard - Tracer Study')
@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Alumni</p>
                <p class="text-3xl font-bold text-[#800000] mt-2">12,480</p>
            </div>
            <div class="w-12 h-12 bg-red-50 text-[#800000] rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
    </div>
    
    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Response Rate</p>
                <p class="text-3xl font-bold text-green-700 mt-2">68.5%</p>
            </div>
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Employed < 6 Months</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">82%</p>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Responses Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-[#800000]">Recent Questionnaire Responses</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm">
                    <th class="px-6 py-4 font-medium">Alumni Name</th>
                    <th class="px-6 py-4 font-medium">Graduation Year</th>
                    <th class="px-6 py-4 font-medium">Study Program</th>
                    <th class="px-6 py-4 font-medium">Current Status</th>
                    <th class="px-6 py-4 font-medium">Date Submitted</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-900 font-medium">Budi Santoso</td>
                    <td class="px-6 py-4 text-gray-500">2022</td>
                    <td class="px-6 py-4 text-gray-500">Informatics Engineering</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Employed</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">Today, 09:42 AM</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-900 font-medium">Siti Aminah</td>
                    <td class="px-6 py-4 text-gray-500">2023</td>
                    <td class="px-6 py-4 text-gray-500">Information Systems</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Further Study</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">Yesterday, 14:15 PM</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-900 font-medium">Andi Wijaya</td>
                    <td class="px-6 py-4 text-gray-500">2023</td>
                    <td class="px-6 py-4 text-gray-500">Computer Science</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Seeking Job</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">Aug 24, 2024</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
