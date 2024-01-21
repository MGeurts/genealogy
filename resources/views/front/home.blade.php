<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('app.home') }}
        </h2>
    </x-slot>

    <div class="py-10 dark:text-neutral-200">
        <div class="flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-5xl mt-6 p-6 bg-white shadow-md overflow-hidden rounded prose">
                <h1>Welcome ...</h1>
                <x-hr.narrow class="!my-2" />

                <h3><b>Genealogy</b> is a free and open-source family tree application to record family members and their relationships.</h3>
                <img src="https://genealogy.kreaweb.be/img/genealogy-001a.webp" class="rounded" alt="Genealogy-001a">
                <img src="https://genealogy.kreaweb.be/img/genealogy-001b.webp" class="rounded" alt="Genealogy-001b">

                <h2>Demo credentials</h2>
                <x-hr.narrow class="!my-2" />

                <div class="rounded bg-info-100 p-4 text-base text-info-800" role="alert">
                    <p><a href="https://genealogy.kreaweb.be/" target="_blank">https://genealogy.kreaweb.be/</a></p>

                    <table>
                        <thead>
                            <tr>
                                <th>E-mail</th>
                                <th>Password</th>
                                <th>Purpose</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>administrator@genealogy.test</td>
                                <td>password</td>
                                <td>to view team BRITISH ROYALS as owner</td>
                                <td>Administrator</td>
                            </tr>
                            <tr>
                                <td>manager@genealogy.test</td>
                                <td>password</td>
                                <td>to view team BRITISH ROYALS as manager</td>
                                <td>Manager</td>
                            </tr>
                            <tr>
                                <td>editor@genealogy.test</td>
                                <td>password</td>
                                <td>to view team BRITISH ROYALS as editor</td>
                                <td>Editor</td>
                            </tr>
                            <tr>
                                <td>member_1@genealogy.test</td>
                                <td>password</td>
                                <td>to view team BRITISH ROYALS as normal member</td>
                                <td>Member</td>
                            </tr>
                            <tr>
                                <td>developer@genealogy.test</td>
                                <td>password</td>
                                <td>to view options reserved for developer, like offcanvas menu</td>
                                <td>Developer</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
