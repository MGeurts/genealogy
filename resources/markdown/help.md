# Help

<hr />

<!-- ---------------------------------------------------------------------------------- -->
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

<hr />

<!-- ---------------------------------------------------------------------------------- -->
## 2. Authentification, multi-tenancy and data accessibility

### a. Users

<img src="https://genealogy.kreaweb.be/img/help/genealogy-002a.webp" class="rounded" alt="Menu">

Users can <b>register</b> themselves. At least a surname, a valid e-mail address and a password is needed.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-002b.webp" class="rounded" alt="Register">

After registration and optional e-mail verification, users can <b>login</b>.<br/>

<img src="https://genealogy.kreaweb.be/img/help/genealogy-002c.webp" class="rounded" alt="Login">

Authenticated users, without invitation, by default belong to (and own) their own <b>personal team</b>.<br/>
New users, after accepting an invitation by email from another user and registering, by default will be logged in to the team they were invited to. Those users by default also have their own personal team at their disposal.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-002d.webp" class="rounded" alt="Team">

<div class="col-span-6 text-sm rounded bg-info-200 p-3 text-info-700" role="alert">
    <b>Two Factor Authentification</b> (2FA) and <b>E-mail Verification</b> are enabled by default but can be configured in <b>config/fortify.php</b>.
</div>

### b. Teams

This application uses <a href="https://jetstream.laravel.com/" target="_blank">Laravel Jetstream 4</a> with the <a href="https://jetstream.laravel.com/features/teams.html" target="_blank">Teams</a> option to implement and enforce <a href="https://en.wikipedia.org/wiki/Multitenancy" target="_blank">multi-tenancy</a>.

Authenticated users can manage their own user profile and teams settings by using the dropdown menus in the top right-hand corner of the menu bar. 

<img src="https://genealogy.kreaweb.be/img/help/genealogy-003.webp" class="rounded" alt="Profile settings">
<img src="https://genealogy.kreaweb.be/img/help/genealogy-004.webp" class="rounded" alt="Team settings">

Users can manage either their personal team or create new teams.<br/>
<span class="text-danger">The personal team and all teams created by a user are also <b>owned</b> by that user.<br/>
Only the owner can invite other (new or already registered) users (by e-mail) to join the owned teams</span>.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-005.webp" class="rounded" alt="Team management">

<div class="col-span-6 text-sm rounded bg-info-200 p-3 text-info-700" role="alert">
    Create a <b>new and seperate team</b> for each <b>family tree</b> you want to manage and invite other users to it</b>.<br/>
    By assigning users the proper <b>role</b> you can define the rights they have in the selected team.
</div>

Authenticated users can only see persons and couples:
<ul>
    <li>created by the teams they own</li>
    <li>created by the teams they were invited to by the team owner as administrator, manager or editor</li>
</ul>

### c. Roles & Permissions

<table>
    <thead>
        <tr>
            <th>Role</th>
            <th>Model</th>
            <th>Permissions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td rowspan="3"><b>Administrator</b></td>
            <td>user</td>
            <td>create, read, update, delete</td>
        </tr>
        <tr>
            <td>person</td>
            <td>create, read, update, delete</td>
        </tr>
        <tr>
            <td>couple</td>
            <td>create, read, update, delete</td>
        </tr>
        <tr>
            <td rowspan="2"><b>Manager</b></td>
            <td>person</td>
            <td>create, read, update, delete</td>
        </tr>
        <tr>
            <td>couple</td>
            <td>create, read, update, delete</td>
        </tr>
        <tr>
            <td rowspan="2"><b>Editor</b></td>
            <td>person</td>
            <td>create, read, update</td>
        </tr>
        <tr>
            <td>couple</td>
            <td>create, read, update</td>
        </tr>
        <tr>
            <td rowspan="2"><b>Member</b></td>
            <td>person</td>
            <td>read</td>
        </tr>
        <tr>
            <td>couple</td>
            <td>read</td>
        </tr>
    </tbody>
</table>


<hr />


<!-- ---------------------------------------------------------------------------------- -->
## 3. Search

<img src="https://genealogy.kreaweb.be/img/help/genealogy-001.webp" class="rounded" alt="Menu">

After login and <span class="text-danger">selecting the proper team</span>, click on the <b>Search</b> button in the top navigation menu.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-010.webp" class="rounded" alt="Search">

Enter a search value in the <b>search box</b>. You can use the character <b class="text-danger">%</b> as a wildcard character.<br/>
Above the search box the number of persons available to / found in the <b>current team</b> are shown.<br/>
You can use the pagination buttons. You can also change the number of persons shown per page.<br/>

The results are shown below the search box, each person is represented in one card.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-012.webp" class="rounded" alt="Menu">

Click on the <b>Profile</b> or <b>Family chart</b> button to see details about that person.
Click on the name of the father or the mother to go that person.

<hr />


<!-- ---------------------------------------------------------------------------------- -->
## 3. Adding persons

After login and <span class="text-danger">selecting the proper team</span>, click on the <b>Search</b> button in the top navigation menu.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-001.webp" class="rounded" alt="Search">

You can add new person by clicking the <b>Add person</b> button above the search bar.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-010.webp" class="rounded" alt="Add person">
<img src="https://genealogy.kreaweb.be/img/help/genealogy-011.webp" class="rounded" alt="Add person">

You can also upload the <b>primary photo</b> for the new person.

Another way to add people is to click on the <b>Add child</b> button on the <b>Children</b> tab of an existing person.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-050.webp" class="rounded" alt="Children">
<img src="https://genealogy.kreaweb.be/img/help/genealogy-051.webp" class="rounded" alt="Add child">

<div class="col-span-6 text-sm rounded bg-info-200 p-3 text-info-700" role="alert">
    You can either create a <b>brand new person</b> or select an <b>existing person</b> as this persons new child.
</div>

<hr />


<!-- ---------------------------------------------------------------------------------- -->
## 4. Persons and relationships

### a. Profile

The personal overview shows all information about the selected person.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-020a.webp" class="rounded" alt="Person overview">

The navigation bar on top allows you to chose some specific items.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-021a.webp" class="rounded" alt="Person menu">

The <b>Profile</b> card shows all general information about the person.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-022a.webp" class="rounded" alt="Profile deceased">

Notice this card shows different data for <b>living</b> and <b>deceased</b> persons.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-022b.webp" class="rounded" alt="Profile living">

You can edit the profile, contact and death data by clicking the blue <b>Edit</b> button in the Profile card header.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-023.webp" class="rounded" alt="Profile edit">
<img src="https://genealogy.kreaweb.be/img/help/genealogy-024.webp" class="rounded" alt="Profile edit profile">

The image uploaded in the <b>Edit profile</b> form, will be the <b>primary photo</b> for the selected person.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-025.webp" class="rounded" alt="Profile edit contact">
<img src="https://genealogy.kreaweb.be/img/help/genealogy-026.webp" class="rounded" alt="Profile edit death">

### b. Photos

<img src="https://genealogy.kreaweb.be/img/help/genealogy-022c.webp" class="rounded" alt="Photo editing">

You can browse through the available photos by using the navigation bar below the photo carousel.<br/>
You can <b>add</b> or <b>delete</b> photos using the buttons above the photo carousel.<br/>
After deletion, the first photo in the carousel wil become the primary photo for the selected person.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-027.webp" class="rounded" alt="Photo add">

### c. Family

The <b>Family</b> card shows the persons parents and the current partner.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-033.webp" class="rounded" alt="Family">

<b>Father</b> and <b>Mother</b> are always referring to the <b>biological</b> father and mother.<br/>
Non-biological parent can be defined by the <b>Parents</b> link.

You can edit the family data by clicking the blue <b>Edit</b> button in the card header.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-034.webp" class="rounded" alt="Family edit">

### d. Partners (Couples)

<img src="https://genealogy.kreaweb.be/img/help/genealogy-040.webp" class="rounded" alt="Partners">

You can add a relationship by clicking the green <b>Add</b> button in the Partners card header.
You can edit a relationship by clicking the blue <b>Edit</b> button in the relationship row.
You can delete a relationship by clicking the red <b>Delete</b> button in the relationship row.<br/>

<img src="https://genealogy.kreaweb.be/img/help/genealogy-041.webp" class="rounded" alt="Partners add">
<img src="https://genealogy.kreaweb.be/img/help/genealogy-042.webp" class="rounded" alt="Partners edit">

### e. Children

<img src="https://genealogy.kreaweb.be/img/help/genealogy-050.webp" class="rounded" alt="Children">

You can add a child by clicking the green <b>Add</b> button in the Children card header.<br/>
You can delete a child relationship <span class="text-danger">(not the person itself)</span> by clicking the red <b>Delete</b> button in the child row.<br/>
The deleted child will remain in the database as a person but just not have the selected person as father or mother anymore.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-051a.webp" class="rounded" alt="Child add">

When adding a child you can either create a <b>brand new person</b> or select an <b>existing person</b>.

### f. Siblings

Siblings are shown on the siblings card.<br/>

<img src="https://genealogy.kreaweb.be/img/help/genealogy-060.webp" class="rounded" alt="Siblings">

A sibling can be <b>full</b>: both the same biological parents as the selected person.<br/>
A sibling can be <b>half</b>: only the biological mother or the biological father are common.<br/>
A sibling can be <b>plus</b>: neither the biological father nor the biological mother are common, but the child is part of the current relationship of the selected person

### g. Ancestors

This shows the selected persons ancestors.<br/>
You can change the tree depth by using the control in the Ancestors card header.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-070.webp" class="rounded" alt="Ancestors">

### h. Descendants

This shows the selected persons descendants.<br/>
You can change the tree depth by using the control in the Descendants card header.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-071.webp" class="rounded" alt="Descendants">

### i. Family chart

This shows the compleet family chart, 3 generations deep.<br/>
Click on a family member name to see that persons details.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-072.webp" class="rounded" alt="Family chart">

<hr />


<!-- ---------------------------------------------------------------------------------- -->
## 5. Birthdays 

After login and selecting the proper team, click on the <b>Birthdays</b> button in the top navigation menu.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-001.webp" class="rounded" alt="Menu">

This shows the upcomming birthdays.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-080.webp" class="rounded" alt="Birthdays">

<hr />


<!-- ---------------------------------------------------------------------------------- -->
## 6. Offcanvas menu 

Users can click a button in the top right-hand menu to open the <b>offcanvas menu</b>.<br/>
On top the users role and permissions in the current team are shown.<br/>
If a user has the proper rights, additional features are presented.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-006a.webp" class="rounded" alt="Offcanvas menu">

### a. Teams & People

<img src="https://genealogy.kreaweb.be/img/help/genealogy-090a.webp" class="rounded" alt="Teams">
<img src="https://genealogy.kreaweb.be/img/help/genealogy-090b.webp" class="rounded" alt="People">

### b. Users & logging

The offcanvas menu allows <b>developers</b> to consult the users and their logging information.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-091a.webp" class="rounded" alt="Users">
<img src="https://genealogy.kreaweb.be/img/help/genealogy-093.webp" class="rounded" alt="User logging 1">
<img src="https://genealogy.kreaweb.be/img/help/genealogy-094.webp" class="rounded" alt="User logging 2">

### c. Backups

The <b>Backups</b> menu item allows <b>developers</b> to make database backups.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-095.webp" class="rounded" alt="Backups">

### d. Log viewer

The <b>Log Viewer</b> menu item allows <b>developers</b> to consult the application log files.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-096.webp" class="rounded" alt="Log viewer">

### e. Dependencies

The <b>Dependencies</b> menu item allows <b>developers</b> to consult the application dependencies.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-097a.webp" class="rounded" alt="Dependencies">

### f. Session

The <b>Dependencies</b> menu item allows <b>developers</b> to consult the application session.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-098.webp" class="rounded" alt="Session">

<hr />


<!-- ---------------------------------------------------------------------------------- -->
## 7. Language

### a. Visitors

Visitor can change the language in the top right-hand menu by using the <b>language selector</b>.<br/>
De default application language is English.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-002a.webp" class="rounded" alt="Language menu">

### b. Authenticated users

Authenticated users can change the language in the top right-hand menu by using the <b>profile editor</b>.<br/>
The selected language is saved in the database for further use.

<img src="https://genealogy.kreaweb.be/img/help/genealogy-002d.webp" class="rounded" alt="Profile editor">

<hr />


<!-- ---------------------------------------------------------------------------------- -->
## 8. Color theme

Visitors and authenticated users can change the color theme in the top right-hand menu by using the <b>theme selctor</b>.<br/>

<img src="https://genealogy.kreaweb.be/img/help/genealogy-002a.webp" class="rounded" alt="Theme selector">

