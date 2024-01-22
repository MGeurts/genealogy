<p align="center"><img src="https://genealogy.kreaweb.be/img/genealogy-logo.svg" alt="Genealogy-logo"/></p>

# Genealogy

## About this project

<b>Genealogy</b> is a free and open-source (family tree) application to record family members and their relationships, build with Laravel 10.

<img src="https://genealogy.kreaweb.be/img/genealogy-001a.webp" class="rounded" alt="Genealogy-001a"/>
<img src="https://genealogy.kreaweb.be/img/genealogy-001b.webp" class="rounded" alt="Genealogy-001b"/>

This application is build using :
<ul>
    <li><a href="https://laravel.com/" target="_blank">Laravel 10</a> (featuring <a href="https://vitejs.dev/" target="_blank">Vite</a>)</li>
    <li><a href="https://jetstream.laravel.com/" target="_blank">Laravel Jetstream 4</a> (featuring <a href="https://jetstream.laravel.com/features/teams.html" target="_blank">Teams</a>)</li>
    <li><a href="https://livewire.laravel.com/" target="_blank">Livewire 3</a></li>
    <li><a href="https://tailwindcss.com/" target="_blank">Tailwind CSS</a></li>
    <li><a href="https://tw-elements.com/" target="_blank">Tailwind Elements</a></li>
    <li><a href="https://tabler-icons.io/" target="_blank">Tabler Icons</a></li>
</ul>

### Logic concept
1. A person can have 1 biological father (1 person, based on <b>father_id</b>)
2. A person can have 1 biological mother (1 person, based on <b>mother_id</b>)
3. A person can have 1 set of parents, biological or not (1 couple of 2 people, based on <b>parents_id</b>)

4. A person can have 0 to many biological children (n people, based on father_id/mother_id)

5. A person can have 0 to many partners (n people), being part of 0 to many couples (opposite or same biological sex)
6. A person can be part of a couple with the same partner multiple times (remarriage or reunite)

7. A couple can have 0 to many children (based on parents_id as a couple or father_id/mother_id individually)
8. A couple can be married or not, still together or separated in the meantime

### Requirements

<ul>
    <li>At least <a href="https://www.php.net/" target="_blank">PHP 8.1</a></li>
</ul>

### License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Demo

<a href="https://genealogy.kreaweb.be/" target="_blank">https://genealogy.kreaweb.be/</a>

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
            <td>**administrator@genealogy.test**</b></td>
            <td>**password**</td>
            <td>to view team BRITISH ROYALS as team **owner**</td>
            <td>Administrator</td>
        </tr>
        <tr>
            <td>manager@genealogy.test</td>
            <td>password</td>
            <td>to view team BRITISH ROYALS as **manager**</td>
            <td>Manager</td>
        </tr>
        <tr>
            <td>editor@genealogy.test</td>
            <td>password</td>
            <td>to view team BRITISH ROYALS as **editor**</td>
            <td>Editor</td>
        </tr>
        <tr>
            <td>member_1@genealogy.test</td>
            <td>password</td>
            <td>to view team BRITISH ROYALS as normal **member**</td>
            <td>Member</td>
        </tr>
        <tr>
            <td>developer@genealogy.test</td>
            <td>password</td>
            <td>to view options reserved for a **developer**, like the offcanvas menu</td>
            <td>Developer</td>
        </tr>
    </tbody>
</table>

## Features

<ul>
    <li>Light/Dark/System theme</li>
    <li>Fully responsive</li>
    <li>Multi-language, English and Dutch already implemented, language setting saved in authenticated users profile</li>
    <li>Multi-tenancy by Jetstream Teams</li>
    <li>Security through Jetstream Teams Roles & Permissions, 2FA & API can be enabled</li>
    <li>Offcanvas menu</li>
    <li>Image upload with possibility of watermarking</li>
</ul>

### Special features

<p>This application has a built-in <b>Backup Manager</b> :
    <ul>
        <li>Backups can be initiated and managed manually</li>
        <li>Backups can be scheludeld by issuing a cron job on your development or production server</li>
        <li>An e-mail will be send after each backup</li>
   </ul>
</p>

<p>This application has a built-in <b>Log Viewer</b> showing :
    <ul>
        <li>INFO    : All scheduled backups</li>
        <li>DEBUG   : All executed database queries, but only in local development mode</li>
        <li>WARNING : All detected N+1 queries, but only in local development mode</li>
        <li>ERROR   : All detected errors</li>
   </ul>
</p>

<p>This application has a built-in <b>User logging</b> :
    <ul>
        <li>User statistics by country of origin</li>
        <li>User statistics by year/month/week/day</li>
   </ul>
</p>

### To do ...

<ul>
    <li>Allow uploading supplemental images for people</li>
    <li>GEDCOM import and export</li>
</ul>

## Installation

create a new project folder, cd into the folder

git clone https://github.com/MGeurts/genealogy.git .

cp .env.example .env 
make the needed changes regarding name, url, database connection & mail server

composer install

php artisan key:generate
php artisan storage:link
php artisan migrate:fresh --seed

php artisan serve

## Contributing

Feel free to submit Issues (for bugs or suggestions) and Pull Requests.

## Help

Visit the <a href="https://genealogy.kreaweb.be/help" target="_blank">demo project</a> to read the documentation.

## Impressum

### Basic idea (2017)

This application is based on an original implementation by <a href="https://github.com/nafiesl/silsilah" target="_blank">Nafies Luthfi</a>. Thanks for your excellent work.

### Design & Development (2024)

This Laravel application is designed and developed by <a href="https://www.kreaweb.be" target="_blank">kreaweb.be</a>.
