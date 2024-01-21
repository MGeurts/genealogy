# Help

## 1. Concept

## 2. Models & relationships

### a. People
### b. Couples
<br/>

## 3. Authentification, multi-tenancy and data accessibility

### a. Users

Users can <b>register</b> themselves. At least a surname, a valid e-mail address and a password is needed.

After registration and optional e-mail verification, users can <b>login</b> and manage their own user profile and teams settings by using the dropdown menus in the top right-hand corner of the menu bar. Authenticated users, without invitation, by default belong to (and own) their own <b>personal team</b>.

<div class="col-span-6 text-sm rounded bg-info-200 p-3 text-info-700" role="alert">
    Tip : <b>Two Factor Authentification</b> (2FA) and <b>E-mail Verification</b> are enabled by default but can be configured in <b>config/fortify.php</b>.
</div>

### b. Teams

This application uses <a href="https://jetstream.laravel.com/" target="_blank">Laravel Jetstream 4</a> with the <a href="https://jetstream.laravel.com/features/teams.html"
        target="_blank">Teams</a> option to implement and enforce <a href="https://en.wikipedia.org/wiki/Multitenancy" target="_blank">multi-tenancy</a>. This ensures that
    registered users can see and manage <span class="text-danger"><b>only the people and couples they own</b></span>.

<ul>
    <li>people created while the users personal team is active</li>
    <li>people created while the a global team is active</li>
</ul>


Users can manage either their personal team or create new teams.
Users the can invite other (new or already registered) users (by e-mail) to join.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-001.webp" class="rounded" alt="Manage Teams">
<br/>
