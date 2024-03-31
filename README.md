<p align="center"><img src="https://genealogy.kreaweb.be/img/genealogy.svg" width="300px" alt="Genealogy"/></p>

# Genealogy

## About this project

<b>Genealogy</b> is a free and open-source (family tree) application to record family members and their relationships, build with PHP (Laravel 11).

<img src="https://genealogy.kreaweb.be/img/help/genealogy-000.webp" class="rounded" alt="Genealogy-000"/>
<img src="https://genealogy.kreaweb.be/img/help/genealogy-020a.webp" class="rounded" alt="Genealogy-020a"/>

This application is build using :

<ul>
    <li><a href="https://laravel.com/" target="_blank">Laravel 11</a> (featuring <a href="https://vitejs.dev/" target="_blank">Vite</a>)</li>
    <li><a href="https://jetstream.laravel.com/" target="_blank">Laravel Jetstream 4</a> (featuring <a href="https://jetstream.laravel.com/features/teams.html" target="_blank">Teams</a>)</li>
    <li><a href="https://livewire.laravel.com/" target="_blank">Livewire 3</a></li>
    <li><a href="https://tailwindcss.com/" target="_blank">Tailwind CSS</a></li>
    <li><a href="https://tw-elements.com/" target="_blank">Tailwind Elements</a></li>
    <li><a href="https://filamentphp.com//" target="_blank">Laravel Filament</a> (only Table Builder)</li>
    <li><a href="https://tabler-icons.io/" target="_blank">Tabler Icons</a></li>
</ul>

### Logic concept

1. A person can have 1 biological father (1 person, based on <b>father_id</b>)
2. A person can have 1 biological mother (1 person, based on <b>mother_id</b>)
3. A person can have 1 set of parents, biological or not (1 couple of 2 people, based on <b>parents_id</b>)

4. A person can have 0 to many biological children (n people, based on father_id/mother_id)

5. A person can have 0 to many partners (n people), being part of 0 to many couples (opposite or same biological sex)
6. A person can be part of a couple with the same partner multiple times (remarriage or reunite)

7. A couple can have 0 to many children (based on <b>parents_id as a couple</b> or <b>father_id/mother_id individually</b>)
8. A couple can be married or not, still together or separated in the meantime

### Requirements

<ul>
    <li>
        At least <a href="https://www.php.net/" target="_blank">PHP</a> 8.2, supporting Laravel 11.<br/>
    </li>
    <li>
        At least <a href="https://www.mysql.com/" target="_blank">MySQL</a> 8.0.1 or <a href="https://mariadb.com/" target="_blank">MariaDB</a> 10.2.2 or an equivalent database, supporting <a href="https://dev.mysql.com/doc/refman/8.0/en/with.html" target="_blank">Recursive Common Table Expressions</a>.
    </li>
</ul>

### License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Demo

<a href="https://genealogy.kreaweb.be/" target="_blank">https://genealogy.kreaweb.be/</a>

<p>This demo has 2 family trees implemented, <b>BRITISH ROYALS</b> and <b>KENNEDY</b>.</p>

<table>
    <thead>
        <tr>
            <th>E-mail</th>
            <th>Password</th>
            <th>Purpose</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><b>administrator@genealogy.test</b></td>
            <td>password</td>
            <td>to view teams <b>BRITISH ROYALS</b> and <b>KENNEDY</b> as team <b>owner</b></td>
        </tr>
        <tr>
            <td><b>manager@genealogy.test</b></td>
            <td>password</td>
            <td>to view team <b>BRITISH ROYALS</b> as <b>manager</b></td>
        </tr>
        <tr>
            <td><b>editor@genealogy.test</b></td>
            <td>password</td>
            <td>to view team <b>KENNEDY</b> as <b>editor</b></td>
        </tr>
        <tr>
            <td><b>member_1@genealogy.test</b></td>
            <td>password</td>
            <td>to view team <b>BRITISH ROYALS</b> as normal <b>member</b></td>
        </tr>
        <tr>
            <td><b>member_4@genealogy.test</b></td>
            <td>password</td>
            <td>to view team <b>KENNEDY</b> as normal <b>member</b></td>
        </tr>
        <tr>
            <td><b>developer@genealogy.test</b></td>
            <td>password</td>
            <td>to view options reserved for the <b>developer</b>, like the <b>user management</b> and access to <b>all persons in all teams</b></td>
        </tr>
    </tbody>
</table>

## Features

<ul>
    <li>Light/Dark/System theme</li>
    <li>Fully responsive</li>
    <li>Multi-language, German, English and Dutch already implemented, language setting saved in authenticated users profile</li>
    <li>Multi-tenancy by Jetstream Teams</li>
    <li>Security through Jetstream Teams Roles & Permissions, 2FA & API can be enabled</li>
    <li>Offcanvas menu</li>
    <li>Multiple image upload with possibility of watermarking, photo carousel</li>
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

<p>This application has a built-in <b>User management & logging</b>, available to the developer :
    <ul>
        <li>User statistics by country of origin</li>
        <li>User statistics by year/month/week/day</li>
   </ul>
</p>

### To Do ...

<ul>
    <li><a href="https://www.gedcom.org/" target="_blank">GEDCOM</a> import and export (help gladly accepted).</li>
</ul>

## Techniques

Both the <b>ancestors</b> and <b>descendants</b> family trees are build using <a href="https://dev.mysql.com/blog-archive/mysql-8-0-labs-recursive-common-table-expressions-in-mysql-ctes" target="_blank">Recursive Common Table Expressions</a> (Recursive CTE). This prevents the N+1 query problem generating the recursive tree family elements and dramatically improves performance.

## Installation

create a new project folder, cd into the folder

`git clone https://github.com/MGeurts/genealogy.git .`

`cp .env.example .env`

make the needed changes regarding name, url, database connection & mail server

`composer install`

`php artisan key:generate`

`php artisan storage:link`

`php artisan migrate:fresh --seed`

`npm install & npm run build`

`php artisan serve` or `npm run dev`

## Docker Installation

`cp env.docker .env`

run following command to initialize project

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

run docker containers

```bash
./vendor/bin/sail up -d
```

`./vendor/bin/sail artisan key:generate`

`./vendor/bin/sail artisan storage:link`

`./vendor/bin/sail artisan migrate:fresh --seed`

`./vendor/bin/sail npm install & npm run build`

`./vendor/bin/sail artisan serve` or `npm run dev`

## Testing

`php artisan test`

## Contributing

Feel free to submit <b>Issues</b> (for bugs or suggestions) and <b>Pull Requests</b>.

## Documentation

The documentation is built-in the applications help.
Visit the <a href="https://genealogy.kreaweb.be/help" target="_blank">demo project</a> to read the documentation.

### Sponsoring

If you like this project, please consider giving it a star and spread the word. Thank you.

## Impressum

### Basic idea (2017)

This application is based on an original idea by <a href="https://github.com/nafiesl/silsilah" target="_blank">Nafies Luthfi</a>. Thanks for your excellent work.

### Design & Development (2024)

This Laravel application is designed and developed by <a href="https://www.kreaweb.be" target="_blank">kreaweb.be</a>.
