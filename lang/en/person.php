<?php

return [
    // Labels
    'biological' => 'biological',
    'person' => 'Person',
    'people' => 'People',

    'family' => 'Family',
    'profile' => 'Profile',

    'partner' => 'Partner',
    'partners' => 'Partners',

    'children' => 'Children',
    'parents' => 'Parents',
    'grandchildren' => 'Grandchildren',
    'siblings' => 'Siblings',
    'ancestors' => 'Ancestors',
    'descendants' => 'Descendants',
    'dead' => 'Dead',
    'death' => 'Death',
    'deceased' => 'Deceased',

    'grandmother' => 'Grandmother',
    'grandfather' => 'Grandfather',
    'nieces' => 'Nieces',
    'nephews' => 'Nephews',
    'cousins' => 'Cousins',
    'uncles' => 'Uncles',
    'aunts' => 'Aunts',
    'relationships' => 'Relationships',
    'birth_order' => 'Birth order',
    'age' => 'Age',
    'years' => '[0,1] Year|[2,*] Years',

    // Actions
    'add_child' => 'Add child',
    'add_person' => 'Add person',
    'add_photo' => 'Add photo',
    'add_relationship' => 'Add relationship',

    'edit' => 'Edit',
    'edit_children' => 'Edit children',
    'edit_contact' => 'Edit contact',
    'edit_death' => 'Edit death',
    'edit_family' => 'Edit family',
    'edit_person' => 'Edit person',
    'edit_profile' => 'Edit profile',
    'edit_relationship' => 'Edit relationship',

    'delete_child' => 'Disconnect child',
    'delete_person' => 'Delete person',
    'delete_relationship' => 'Delete relationship',

    // Attributes
    'id' => 'ID',
    'name' => 'Name',
    'firstname' => 'First name',
    'surname' => 'Surname',
    'birthname' => 'Birthname',
    'nickname' => 'Nickname',
    'sex' => 'Sex',
    'gender' => 'Gender identity',
    'father' => 'Father',
    'mother' => 'Mother',
    'parent' => 'Parent',
    'dob' => 'Date of birth',
    'yob' => 'Year of birth',
    'pob' => 'Place of birth',
    'dod' => 'Date of death',
    'yod' => 'Year of death',
    'pod' => 'Place of death',
    'email' => 'Email',
    'password' => 'Password',
    'address' => 'Address',
    'street' => 'Street',
    'number' => 'Number',
    'postal_code' => 'Postal code',
    'city' => 'City',
    'province' => 'Province',
    'state' => 'State',
    'country' => 'Country',
    'phone' => 'Phone',

    'cemetery' => 'Cemetery',
    'cemetery_location' => 'Cemetery Location',

    // Photo
    'edit_photos' => 'Edit photos',
    'photo_deleted' => 'Photo deleted',
    'photo' => 'Photo',
    'photos' => 'Photos',
    'photos_existing' => 'Existing photos',
    'set_primary' => 'Set as primary',
    'upload_photo' => 'Upload photo',
    'upload_photos' => 'Upload photos',
    'upload_photo_primary' => 'Upload (or re-upload) primary photo',
    'update_photo_tip' => 'Drag and drop your new photos here',

    // Messages
    'yod_not_matching_dod' => 'The Year of death must match the Date of death (:value).',
    'yod_before_dob' => 'The Year of death can not be before the Date of birth (:value).',
    'yod_before_yob' => 'The Year of death can not be before the Year of birth (:value).',

    'dod_not_matching_yod' => 'The Date of death must match the Year of death (:value).',
    'dod_before_dob' => 'The Date of death can not be before the Date of birth (:value).',
    'dod_before_yob' => 'The Date of death can not be before the Year of birth (:value).',

    'yob_not_matching_dob' => 'The Year of birth must match the Date of birth (:value).',
    'yob_after_dod' => 'The Year of birth can not be after the Date of death (:value).',
    'yob_after_yod' => 'The Year of birth can not be after the Year of death (:value).',

    'dob_not_matching_yob' => 'The Date of birth must match the Year of birth (:value).',
    'dob_after_dod' => 'The Date of birth can not be after the Date of death (:value).',
    'dob_after_yod' => 'The Date of birth can not be after the Year of death (:value).',
];
