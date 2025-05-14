<?php

declare(strict_types=1);

return [
    // Etiketler
    'user'  => 'Kullanıcı',
    'users' => 'Kullanıcılar',

    // Eylemler
    'edit' => 'Düzenle',

    // Özellikler
    'id'                      => 'ID',
    'name'                    => 'Ad',
    'firstname'               => 'İsim',
    'surname'                 => 'Soyisim',
    'email'                   => 'E-posta',
    'password'                => 'Şifre',
    'current_password'        => 'Mevcut şifre',
    'new_password'            => 'Yeni şifre',
    'confirm_password'        => 'Şifreyi onayla',
    'confirm_new_password'    => 'Yeni şifreyi onayla',
    'language'                => 'Dil',
    'timezone'                => 'Zaman dilimi',
    'developer'               => 'Geliştirici',
    'team'                    => 'Takım',
    'current_team'            => 'Mevcut takım',
    'email_verified'          => 'E-posta doğrulandı',
    'email_verified_at'       => 'E-posta doğrulama zamanı',
    'two_factor_confirmed_at' => '2FA doğrulama zamanı',
    'seen_at'                 => 'Son görülme zamanı',

    // Fotoğraf
    'photo'        => 'Avatar',
    'select_photo' => 'Yeni bir avatar seç',
    'remove_photo' => 'Avatarı kaldır',

    // Silme onay özellikleri
    'delete'                => 'Kullanıcıyı Sil',
    'delete_confirm'        => 'Bu kullanıcıyı silmek istediğinizden emin misiniz?',
    'delete_confirm_button' => 'Evet, eminim',

    // Mesajlar
    'profile'                    => 'Profil',
    'profile_information'        => 'Profil Bilgileri',
    'profile_information_update' => 'Hesabınızın profil bilgilerini ve e-posta adresini güncelleyin.',

    'update_password'        => 'Şifreyi Güncelle',
    'update_password_secure' => 'Hesabınızın güvenliğini sağlamak için uzun, rastgele bir şifre kullanın.',
    'password_no_match'      => 'Girilen şifre mevcut şifrenizle eşleşmiyor.',

    '2fa'              => 'İki Faktörlü Kimlik Doğrulama',
    '2fa_add'          => 'Hesabınıza iki faktörlü kimlik doğrulama ekleyerek ek güvenlik sağlayın.',
    '2fa_finish'       => 'İki faktörlü kimlik doğrulamayı etkinleştirmeyi tamamlayın',
    '2fa_enabled'      => 'İki faktörlü kimlik doğrulamayı etkinleştirdiniz.',
    '2fa_not_enabled'  => 'Henüz iki faktörlü kimlik doğrulamayı etkinleştirmediniz.',
    '2fa_message'      => 'İki faktörlü kimlik doğrulama etkinleştirildiğinde, kimlik doğrulama sırasında güvenli, rastgele bir token istenecektir. Bu tokeni telefonunuzun Google Authenticator uygulamasından alabilirsiniz.',
    '2fa_to_finish'    => 'İki faktörlü kimlik doğrulamayı etkinleştirmeyi tamamlamak için, telefonunuzun kimlik doğrulama uygulamasını kullanarak aşağıdaki QR kodunu tarayın veya kurulum anahtarını girin ve oluşturulan OTP kodunu sağlayın.',
    '2fa_enabled_scan' => 'İki faktörlü kimlik doğrulama artık etkin. Telefonunuzun kimlik doğrulama uygulamasını kullanarak aşağıdaki QR kodunu tarayın veya kurulum anahtarını girin.',
    '2fa_setup_key'    => 'Kurulum Anahtarı',
    '2fa_code'         => 'Kod',
    '2fa_store_codes'  => 'Bu kurtarma kodlarını güvenli bir şifre yöneticisinde saklayın. İki faktörlü kimlik doğrulama cihazınız kaybolursa hesabınıza erişimi geri kazanmak için kullanılabilirler.',
    '2fa_enable'       => 'Etkinleştir',
    '2fa_regenerate'   => 'Kurtarma Kodlarını Yeniden Oluştur',
    '2fa_confirm'      => 'Onayla',
    '2fa_show'         => 'Kurtarma Kodlarını Göster',
    'cancel'           => 'İptal',
    '2fa_disable'      => 'Devre Dışı Bırak',

    'browser_sessions'         => 'Tarayıcı Oturumları',
    'browser_sessions_manage'  => 'Diğer tarayıcılar ve cihazlardaki aktif oturumlarınızı yönetin ve çıkış yapın.',
    'browser_sessions_message' => 'Gerekirse, diğer cihazlarınızdaki tüm tarayıcı oturumlarınızdan çıkış yapabilirsiniz. Aşağıda bazı son oturumlarınız listelenmiştir; ancak, bu liste kapsamlı olmayabilir. Hesabınızın tehlikeye girdiğini düşünüyorsanız, şifrenizi de güncellemelisiniz.',
    'Unknown'                  => 'Bilinmeyen',
    'this_device'              => 'Bu cihaz',
    'last_active'              => 'Son aktif',
    'log_out'                  => 'Diğer Tarayıcı Oturumlarından Çıkış Yap',
    'enter_password'           => 'Diğer cihazlarınızdaki tarayıcı oturumlarınızdan çıkış yapmak istediğinizi onaylamak için lütfen şifrenizi girin.',
    'done'                     => 'Tamamlandı.',

    'delete_account'             => 'Hesabı sil',
    'delete_account_permanently' => 'Hesabınızı kalıcı olarak silin.',
    'once_deleted'               => 'Hesabınız silindiğinde, tüm kaynakları ve verileri kalıcı olarak silinecektir. Hesabınızı silmeden önce, saklamak istediğiniz herhangi bir veri veya bilgiyi indiriniz.',
    'sure'                       => 'Hesabınızı silmek istediğinizden emin misiniz? Hesabınız silindiğinde, tüm kaynakları ve verileri kalıcı olarak silinecektir. Hesabınızı kalıcı olarak silmek istediğinizi onaylamak için lütfen şifrenizi girin.',
    'can_not_delete'             => 'Hesabınız geçerli veriler içerdiği için silinemez.',

    'email_unverified'               => 'E-posta adresiniz henüz doğrulanmadı.',
    'click_resend_verification_mail' => 'Doğrulama e-postasını yeniden gönder.',
    'verififacion_mail_send'         => 'E-posta adresinize yeni bir doğrulama bağlantısı gönderildi.',

];
