<?php

return [
    // 标签
    'biological' => '',
    'person'     => '姓名',
    'people'     => '亲友',

    'family'  => '家庭',
    'profile' => '信息',

    'partner'  => '现任配偶',
    'partners' => '配偶',

    'children'      => '孩子',
    'parents'       => '养父母',
    'grandchildren' => '孙子',
    'siblings'      => '兄弟姐妹',
    'ancestors'     => '祖先',
    'descendants'   => '后代',
    'dead'          => '已故',
    'death'         => '已故',
    'deceased'      => '已故',

    'grandmother'   => '祖母',
    'grandfather'   => '祖父',
    'nieces'        => '侄女',
    'nephews'       => '侄子',
    'cousins'       => '堂兄弟',
    'uncles'        => '叔伯',
    'aunts'         => '姑姑',
    'relationships' => '关系',
    'birth_order'   => '出生顺序',
    'age'           => '年龄',
    'years'         => '[0,1] 岁|[2,*] 岁',

    // 操作
    'add_father'                     => '添加父亲',
    'add_new_person_as_father'       => '添加新的亲友为父亲',
    'add_existing_person_as_father'  => '选择现有亲友为父亲',
    'add_mother'                     => '添加母亲',
    'add_new_person_as_mother'       => '添加新的亲友为母亲',
    'add_existing_person_as_mother'  => '选择现有亲友为母亲',
    'add_child'                      => '添加孩子',
    'add_new_person_as_child'        => '添加新的亲友为孩子',
    'add_existing_person_as_child'   => '选择现有亲友为孩子',
    'add_person'                     => '添加人物',
    'add_new_person_as_partner'      => '添加新的亲友为配偶',
    'add_existing_person_as_partner' => '选择现有亲友为配偶',
    'add_person_in_team'             => '在家族 : :team 中添加人物',
    'add_photo'                      => '添加照片',
    'add_relationship'               => '添加关系',

    'edit'              => '编辑',
    'edit_children'     => '编辑孩子',
    'edit_contact'      => '编辑联系信息',
    'edit_death'        => '编辑死亡信息',
    'edit_family'       => '编辑家庭',
    'edit_person'       => '编辑成员',
    'edit_profile'      => '编辑信息',
    'edit_relationship' => '编辑关系',

    'delete_child'        => '移除孩子',
    'delete_person'       => '删除成员',
    'delete_relationship' => '删除关系',

    // 属性
    'id'          => 'ID',
    'name'        => '姓名',
    'firstname'   => '姓',
    'surname'     => '名',
    'story'       => '简介',
    'nickname'    => '昵称',
    'sex'         => '性别',
    'gender'      => '性别认同',
    'father'      => '亲生父亲',
    'mother'      => '亲生母亲',
    'parent'      => '养父母',
    'dob'         => '出生日期',
    'yob'         => '出生年份',
    'pob'         => '出生地',
    'dod'         => '死亡日期',
    'yod'         => '死亡年份',
    'pod'         => '死亡地',
    'email'       => '电子邮件',
    'password'    => '密码',
    'address'     => '地址',
    'street'      => '街道',
    'number'      => '门牌号',
    'postal_code' => '邮政编码',
    'city'        => '城市',
    'province'    => '省',
    'state'       => '区、县',
    'country'     => '国家',
    'phone'       => '电话',

    'cemetery'          => '墓地',
    'cemetery_location' => '墓地位置',

    // files
    'upload_files'     => '上传文件',
    'files'            => '文件',
    'files_saved'      => '[0] 没有保存文件|[1] 文件已保存|[2,*] 文件已保存',
    'file'             => '文件',
    'file_deleted'     => '文件已删除',
    'update_files_tip' => '将新文件拖放到此处',

    // 照片
    'edit_photos'          => '编辑照片',
    'photo_deleted'        => '照片已删除',
    'photo'                => '照片',
    'photos'               => '照片',
    'photos_existing'      => '现有照片',
    'set_primary'          => '设为主照片',
    'upload_photo'         => '上传照片',
    'upload_photos'        => '上传照片',
    'upload_photo_primary' => '上传（或重新上传）主照片',
    'update_photo_tip'     => '拖放新照片到这里',

    // 消息
    'yod_not_matching_dod' => '死亡年份必须与死亡日期匹配（:value）。',
    'yod_before_dob'       => '死亡年份不能早于出生日期（:value）。',
    'yod_before_yob'       => '死亡年份不能早于出生年份（:value）。',

    'dod_not_matching_yod' => '死亡日期必须与死亡年份匹配（:value）。',
    'dod_before_dob'       => '死亡日期不能早于出生日期（:value）。',
    'dod_before_yob'       => '死亡日期不能早于出生年份（:value）。',

    'yob_not_matching_dob' => '出生年份必须与出生日期匹配（:value）。',
    'yob_after_dod'        => '出生年份不能晚于死亡日期（:value）。',
    'yob_after_yod'        => '出生年份不能晚于死亡年份（:value）。',

    'dob_not_matching_yob' => '出生日期必须与出生年份匹配（:value）。',
    'dob_after_dod'        => '出生日期不能晚于死亡日期（:value）。',
    'dob_after_yod'        => '出生日期不能晚于死亡年份（:value）。',

    'not_found' => '没有找到此人',
    'use_tab'   => '使用选项卡',

    'insert_tip_1' => '输入姓、名、昵称、简介的关键字。',
    'insert_tip_2' => '不要混合输入',
];
