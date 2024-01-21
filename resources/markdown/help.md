# Help

## 1. Concept: Models & relationships

### a. People
<ul>
    <li>A person can have 1 biological father (1 person, based on <b>father_id</b>)</li>
    <li>A person can have 1 biological mother (1 person, based on <b>mother_id</b>)</li>
    <li>A person can have 1 set of parents, biological or not (1 couple of 2 people, based on <b>parents_id</b>)</li>
    <li>A person can have 0 to many biological children (n people, based on father_id/mother_id)</li>
    <li>A person can have 0 to many partners (n people), being part of 0 to many couples (opposite or same biological sex)</li>
    <li>A person can be part of a couple with the same partner multiple times (remarriage or reunite)</li>
</ul>

### b. Couples
<ul>
    <li>A couple can have 0 to many children (based on parents_id as a couple or father_id/mother_id individually)</li>
    <li>A couple can be married or not, still together or separated in the meantime</li>
</ul>

## 2. Authentification, multi-tenancy and data accessibility

### a. Users

<img src="https://genealogy.kreaweb.be/img/help/genealogy-002a.webp" class="rounded" alt="Menu">

Users can <b>register</b> themselves. At least a surname, a valid e-mail address and a password is needed.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-002b.webp" class="rounded" alt="Register">

After registration and optional e-mail verification, users can <b>login</b>.<br/>

<img src="https://genealogy.kreaweb.be/img/help/genealogy-002c.webp" class="rounded" alt="Login">

Authenticated users, without invitation, by default belong to (and own) their own <b>personal team</b>.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-002d.webp" class="rounded" alt="Team">

<div class="col-span-6 text-sm rounded bg-info-200 p-3 text-info-700" role="alert">
    Tip : <b>Two Factor Authentification</b> (2FA) and <b>E-mail Verification</b> are enabled by default but can be configured in <b>config/fortify.php</b>.
</div>

### b. Teams

This application uses <a href="https://jetstream.laravel.com/" target="_blank">Laravel Jetstream 4</a> with the <a href="https://jetstream.laravel.com/features/teams.html" target="_blank">Teams</a> option to implement and enforce <a href="https://en.wikipedia.org/wiki/Multitenancy" target="_blank">multi-tenancy</a>.

Authenticated users can manage their own user profile and teams settings by using the dropdown menus in the top right-hand corner of the menu bar. 

<img src="https://genealogy.kreaweb.be/img/help/genealogy-003.webp" class="rounded" alt="Profile settings">
<img src="https://genealogy.kreaweb.be/img/help/genealogy-004.webp" class="rounded" alt="Team settings">

Users can manage either their personal team or create new teams.
The personal team and all teams created by the user are <b>owned</b> by the current user. The owner can invite other (new or already registered) users (by e-mail) to join.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-005.webp" class="rounded" alt="Team management">

<br/>
