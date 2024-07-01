## Help

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

<img src="img/help/genealogy-002aa.webp" class="rounded" alt="Menu">

Users can <b>register</b> themselves.<br/>
At least a <b>surname</b>, a valid <b>e-mail</b> address, a <b>language</b>, a <b>timezone</b> and a <b>password</b> are required.

<img src="img/help/genealogy-002bb.webp" class="rounded" alt="Register">

After registration and optional e-mail verification, users can <b>login</b>.<br/>

<img src="img/help/genealogy-002cc.webp" class="rounded" alt="Login">

Authenticated users, without invitation, by default belong to (and own) their own <b>personal team</b>.<br/>
New users, after accepting an invitation by email from another user and registering, by default will be logged in to the team they were invited to. Those users by default also have their own personal team at their disposal.

<img src="img/help/genealogy-002d.webp" class="rounded" alt="Team">

<b>Two Factor Authentification</b> (2FA) and <b>E-mail Verification</b> can be enabled and configured in <b>config/fortify.php</b>.

### b. User account and profile

Authenticated users can manage their account and user profile by using the dropdown menu in the top right-hand corner of the menu bar.

<img src="img/help/genealogy-003.webp" class="rounded" alt="Profile settings">

<img src="img/help/genealogy-005aa.webp" class="rounded" alt="User profile">

### c. Teams

This application uses <a href="https://jetstream.laravel.com/" target="_blank">Laravel Jetstream</a> with the <a href="https://jetstream.laravel.com/features/teams.html" target="_blank">Teams</a> option to implement and enforce <a href="https://en.wikipedia.org/wiki/Multitenancy" target="_blank">multi-tenancy</a>.

Authenticated users can manage their teams and teams settings by using the dropdown menu in the top right-hand corner of the menu bar.

<img src="img/help/genealogy-004.webp" class="rounded" alt="Team settings">

Users can manage either their personal team or create new teams.<br/>
<span style="color:red">
The personal team and all teams created by a user are also <b>owned</b> by that user.<br/>
Only the owner can invite other (new or already registered) users (by e-mail) to join the owned teams.
</span>

<img src="img/help/genealogy-005b.webp" class="rounded" alt="Team management">

<span style="color:red">
    Create a <b>new and seperate team</b> for each <b>family tree</b> you want to manage and invite other users to it</b>.<br/>
    By assigning users the proper <b>role</b> you can define the rights they have in the selected team.
</span>

Authenticated users can only see <b>people</b> and <b>couples</b>:

<ul>
    <li>created by the teams they <b>own</b> themselves</li>
    <li>created by the teams they were invited to <b>by the team owner</b> as either <b>administrator</b>, <b>manager</b>, <b>editor</b> or <b>member</b></li>
</ul>

### d. Roles & permissions

<table>
    <thead>
        <tr>
            <th style="text-align:left">Role</th>
            <th style="text-align:left">Model</th>
            <th style="text-align:left">Permissions</th>
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

<img src="img/help/genealogy-001.webp" class="rounded" alt="Menu">

After login and <span style="color:red">selecting the proper team</span>, click on the <b>Search</b> button in the top navigation menu.

<img src="img/help/genealogy-010a.webp" class="rounded" alt="Search">

Enter a search value in the <b>search box</b>. You can use the character <b style="color:red">%</b> as a wildcard character.<br/>
Insert either a <b>surname</b>, a <b>firstname</b>, a <b>birthname</b> or a <b>nickname</b>. Do not combine!<br/>
Above the search box the number of people available to / found in the <b>current team</b> are shown.<br/>

The results are shown in a grid below the search box, each person is represented in one card.<br/>
You can use the pagination buttons. You can also change the number of people shown per page.

<img src="img/help/genealogy-012.webp" class="rounded" alt="Menu">

Click on the <b>Profile</b> or <b>Family chart</b> button to see details about that person.<br/>
Click on the father's or mother's name to visit the parents.

<hr />

<!-- ---------------------------------------------------------------------------------- -->

## 4. Adding people

### a. New person

After login and <span style="color:red">selecting the proper team</span>, click on the <b>Search</b> button in the top navigation menu.

<img src="img/help/genealogy-001.webp" class="rounded" alt="Search">

You can add a new person by clicking the <b>Add person</b> button above the search bar.

<img src="img/help/genealogy-010a.webp" class="rounded" alt="Add person">
<img src="img/help/genealogy-011b.webp" class="rounded" alt="Add person">

### b. New person being a father or mother

Another way to add people is to click on the <b>Add father</b> or <b>Add mother</b> tab in the <b>Family</b> context menu of an existing person.<br/>
These options are only available if the existing person doesn't have a father or mother yet.

<img src="img/help/genealogy-033a.webp" class="rounded" alt="Partners">
<img src="img/help/genealogy-035a.webp" class="rounded" alt="Add father">
<img src="img/help/genealogy-036a.webp" class="rounded" alt="Add mother">

<div style="color:red">
    You can either create a <b>brand new person</b> or select an <b>existing person</b> as the person's new father or mother.
</div>

### c. New person being a partner

Another way to add people is to click on the <b>Add relationship</b> tab in the <b>Partners</b> context menu of an existing person.

<img src="img/help/genealogy-055.webp" class="rounded" alt="Partners">
<img src="img/help/genealogy-056.webp" class="rounded" alt="Add relationship">

<div style="color:red">
    You can either create a <b>brand new person</b> or select an <b>existing person</b> as the person's new partner.
</div>

### d. New person being a child

A last way to add people is to click on the <b>Add child</b> tab in the <b>Children</b> context menu of an existing person.

<img src="img/help/genealogy-050a.webp" class="rounded" alt="Children">
<img src="img/help/genealogy-051c.webp" class="rounded" alt="Add child">

<div style="color:red">
    You can either create a <b>brand new person</b> or select an <b>existing person</b> as the person's new child.
</div>

<hr />

<!-- ---------------------------------------------------------------------------------- -->

## 5. Persons and relationships

### a. Profile

The personal overview shows all information about the selected person.

<img src="img/help/genealogy-020.webp" class="rounded" alt="Person overview">

The navigation bar on top allows you to chose some specific items.

<img src="img/help/genealogy-021b.webp" class="rounded" alt="Person menu">

The <b>Profile</b> card shows all general information about the person.

<img src="img/help/genealogy-022aa.webp" class="rounded" alt="Profile deceased">

Notice this card shows different data for <b>living</b> and <b>deceased</b> people.

<img src="img/help/genealogy-022bb.webp" class="rounded" alt="Profile living">

You can edit the <b>profile</b>, <b>contact</b> and <b>death</b> data by choosing the corresponding tab in the context menu.

<img src="img/help/genealogy-023a.webp" class="rounded" alt="Profile edit">
<img src="img/help/genealogy-024a.webp" class="rounded" alt="Profile edit profile">
<img src="img/help/genealogy-025a.webp" class="rounded" alt="Profile edit contact">
<img src="img/help/genealogy-026a.webp" class="rounded" alt="Profile edit death">

### b. Photos

<img src="img/help/genealogy-022cc.webp" class="rounded" alt="Photo editing">

You can browse through the available photos by using the navigation bar above the photo carousel.<br/>
You can manage photos by choosing the corresponding tab in the context menu.

<img src="img/help/genealogy-023a.webp" class="rounded" alt="Profile edit">
<img src="img/help/genealogy-027a.webp" class="rounded" alt="Photo add">

You can <b>upload</b> (multiple) new images.<br/>
You can <b>download</b> or <b>delete</b> existing images.<br/>
After deleting the primary photo, the first image in the collection wil become the new primary photo.<br/>
You can also set the primary image by clicking the <b>Star</b> button.

### c. Family

The <b>Family</b> card shows the person's parents and the current partner.

<img src="img/help/genealogy-033.webp" class="rounded" alt="Family">

<b>Father</b> and <b>Mother</b> are always referring to the <b>biological</b> father and mother.<br/>
Non-biological parent can be defined by the <b>Parents</b> link.

You can edit the family data by selecting the <b>Edit</b> option in the family context menu.<br/>
In case the person's father or mother are not yet known, you can add or edit them directly in the family context menu.

<img src="img/help/genealogy-033a.webp" class="rounded" alt="Family edit">
<img src="img/help/genealogy-034.webp" class="rounded" alt="Family">

### d. Partners (Couples)

<img src="img/help/genealogy-040a.webp" class="rounded" alt="Partners">

You can <b>add</b>, <b>edit</b> or <b>delete</b> a relationship by choosing the corresponding tab in the context menu.
When deleting a relationship, the ex-partner stays in the collection as a separate person.
Under normal circumstances, you should only delete relationships when entered by mistake.
You can end every exiting relationship by setting the relation as ended, with or without specifying the end date.

<img src="img/help/genealogy-041b.webp" class="rounded" alt="Partners add">

When adding a partner you can either create a brand new person or select an existing person as the new partner.

<img src="img/help/genealogy-055.webp" class="rounded" alt="Partners add">
<img src="img/help/genealogy-042a.webp" class="rounded" alt="Partners edit">

### e. Children

<img src="img/help/genealogy-050a.webp" class="rounded" alt="Children">

You can add a child or disconnect existing children by choosing the corresponding tab in the context menu.
The disconnected child will remain in the database as a person but just not have the selected person as father or mother anymore.

<img src="img/help/genealogy-052.webp" class="rounded" alt="Child add">
<img src="img/help/genealogy-051c.webp" class="rounded" alt="Child add">

When adding a child you can either create a <b>brand new person</b> or select an <b>existing person</b>.

### f. Siblings

Siblings are shown on the siblings card.<br/>

<img src="img/help/genealogy-060a.webp" class="rounded" alt="Siblings">

A sibling can be <b>full</b>: both the same biological parents as the selected person.<br/>
A sibling can be <b>half</b>: only the biological mother or the biological father are common.<br/>
A sibling can be <b>plus</b>: neither the biological father nor the biological mother are common, but the child is part of the current relationship of the selected person

### g. Ancestors

This shows the selected person's ancestors.<br/>
You can change the tree depth by using the control in the Ancestors card header.

<img src="img/help/genealogy-070.webp" class="rounded" alt="Ancestors">

### h. Descendants

This shows the selected person's descendants.<br/>
You can change the tree depth by using the control in the Descendants card header.

<img src="img/help/genealogy-071.webp" class="rounded" alt="Descendants">

### i. Family chart

This shows the compleet family chart, 3 generations deep.<br/>
Click on a family member name to see that person's family chart.

<img src="img/help/genealogy-072.webp" class="rounded" alt="Family chart">

### j. Files

This shows the files attached to the person.<br/>
You can use this feature to attach documentation.

<img src="img/help/genealogy-074.webp" class="rounded" alt="Files">

You can upload (multiple) new documents.<br/>
You can rearrange the order of the documents by clicking the <b>Up</b> or <b>Down</b> buttons.<br/>
You can download documents by clicking the <b>Download</b> button or open them in a seperate window by clicking on the document icon.<br/>
You can delete a document by clicking on the <b>Delete</b> button.

### k. History

This shows the history of the selected person.<br/>

<img src="img/help/genealogy-073.webp" class="rounded" alt="History">

<hr />

<!-- ---------------------------------------------------------------------------------- -->

## 6. Birthdays

After login and selecting the proper team, click on the <b>Birthdays</b> button in the top navigation menu.

<img src="img/help/genealogy-001.webp" class="rounded" alt="Menu">

This shows the upcomming birthdays.

<img src="img/help/genealogy-080.webp" class="rounded" alt="Birthdays">

<hr />

<!-- ---------------------------------------------------------------------------------- -->

## 7. Offcanvas menu

Users can click a button in the top right-hand menu to open the <b>offcanvas menu</b>.<br/>
On top the users role and permissions in the current team are shown.<br/>
If a user has the proper rights, additional features are presented.

<img src="img/help/genealogy-006.webp" class="rounded" alt="Offcanvas menu">

### a. Teams & people

The offcanvas menu allows <b>developers</b> to consult all teams and people.

<img src="img/help/genealogy-090aa.webp" class="rounded" alt="Teams">
<img src="img/help/genealogy-090bb.webp" class="rounded" alt="People">

### b. Users & logging

The offcanvas menu allows <b>developers</b> to consult the users and their logging information.

<img src="img/help/genealogy-091aa.webp" class="rounded" alt="Users">
<img src="img/help/genealogy-093.webp" class="rounded" alt="User logging 1">
<img src="img/help/genealogy-094.webp" class="rounded" alt="User logging 2">

### c. Backups

The <b>Backups</b> menu item allows <b>developers</b> to manage database backups.

<img src="img/help/genealogy-095.webp" class="rounded" alt="Backups">

### d. Log viewer

The <b>Log Viewer</b> menu item allows <b>developers</b> to consult the application log files.

<img src="img/help/genealogy-096a.webp" class="rounded" alt="Log viewer">

### e. Dependencies

The <b>Dependencies</b> menu item allows <b>developers</b> to consult the application dependencies.

<img src="img/help/genealogy-097.webp" class="rounded" alt="Dependencies">

### f. Session

The <b>Session</b> menu item allows <b>developers</b> to consult the application session.

<img src="img/help/genealogy-098.webp" class="rounded" alt="Session">

<hr />

<!-- ---------------------------------------------------------------------------------- -->

## 8. Language & Timezone

### a. Visitors

Visitor can change the language in the top right-hand menu by using the <b>language selector</b>.<br/>
De <b>default</b> application language is <b>English</b>.

<img src="img/help/genealogy-002aa.webp" class="rounded" alt="Language menu">

### b. Authenticated users

Authenticated users can change the language and the timezone in the top right-hand menu by using the <b>profile editor</b>.<br/>
The selected language and timezone are saved to the database.

<img src="img/help/genealogy-002d.webp" class="rounded" alt="Profile editor">

<hr />

<!-- ---------------------------------------------------------------------------------- -->

## 9. Color theme

Users can change the color theme in the top right-hand menu by using the <b>theme icon</b>.<br/>
The <b>default</b> application theme is <b>Dark mode</b>.

<img src="img/help/genealogy-002aa.webp" class="rounded" alt="Theme selector">
