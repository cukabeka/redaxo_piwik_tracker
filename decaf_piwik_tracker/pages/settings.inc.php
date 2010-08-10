<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id$
 */
$mypage = "decaf_piwik_tracker";

if (!file_exists($REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/config.ini.php'))
{
  echo rex_warning($I18N->msg('piwik_config_missing'));
  exit;
}


$allow_url_fopen = ini_get('allow_url_fopen');

if (!$allow_url_fopen) 
{
  $tracking_types = array('Javascript');
  echo rex_warning($I18N->msg('piwik_allow_url_fopen_off'));
} 
else 
{
  $tracking_types = array('Javascript', 'PHP');
}

$message = FALSE;

if (rex_post('btn_save', 'string') != '')
{
  $file = $REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/config.ini.php';
  $message = rex_is_writable($file);

  if($message === true)
  {
    $message  = $I18N->msg('piwik_config_saved_error');
    $tpl      = rex_get_file_contents($REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/_config.ini.php');
    $search   = array();
    $replace  = array();

    foreach($_POST as $key => $val)
    {
      $search[]   = '@@'.$key.'@@';
      $replace[]  = $val;
    }
    $config_str = str_replace($search, $replace, $tpl);
    if (file_put_contents($REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/config.ini.php', $config_str))
    {
      $message  = $I18N->msg('piwik_config_saved_successful');
    }
  }
}

$piwik_config = parse_ini_file($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/config/config.ini.php', true);

$sel_tracking_method = new rex_select();
$sel_tracking_method->setId('piwik_tracking_method');
$sel_tracking_method->setName('tracking_method');
$sel_tracking_method->setSize(1);
$sel_tracking_method->setSelected($piwik_config['piwik']['tracking_method']);
foreach($tracking_types as $type)
$sel_tracking_method->addOption($type,$type);

if($message) 
{
  echo rex_info($message);
}
?>

<div class="rex-addon-output">
  <div id="rex-addon-editmode" class="rex-form">
    <form action="" method="post">
      <fieldset class="rex-form-col-1">
        <div class="rex-form-wrapper">
          <h3 class="rex-hl2"><?php echo $I18N->msg('piwik_configuration'); ?></h3>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="fromname"><?php echo $I18N->msg('piwik_tracker_url'); ?></label>
              <input type="text" name="tracker_url" id="piwik_tracker_url" value="<?php echo $piwik_config['piwik']['tracker_url'] ?>" placeholder="<?php echo $I18N->msg('piwik_tracker_url_placeholder') ?>" />
            </p>
          </div>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="fromname"><?php echo $I18N->msg('piwik_site_id'); ?></label>
              <input type="text" name="site_id" id="piwik_site_id" value="<?php echo $piwik_config['piwik']['site_id'] ?>"  placeholder="<?php echo $I18N->msg('piwik_site_id_placeholder') ?>" />
            </p>
          </div>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="fromname"><?php echo $I18N->msg('piwik_login'); ?></label>
              <input type="text" name="login" id="piwik_login" value="<?php echo $piwik_config['piwik']['login'] ?>"  placeholder="<?php echo $I18N->msg('piwik_login_placeholder') ?>" />
            </p>
          </div>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="fromname"><?php echo $I18N->msg('piwik_md5_pass'); ?></label>
              <input type="text" name="pass_md5" id="piwik_md5_pass" value="<?php echo $piwik_config['piwik']['pass_md5'] ?>" placeholder="<?php echo $I18N->msg('piwik_md5_pass_placeholder') ?>" />
            </p>
          </div>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="fromname"><?php echo $I18N->msg('piwik_token_auth'); ?></label>
              <input type="text" name="token_auth" id="piwik_token_auth" value="<?php echo $piwik_config['piwik']['token_auth'] ?>" placeholder="<?php echo $I18N->msg('piwik_token_auth_placeholder') ?>" />
            </p>
          </div>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="encoding"><?php echo $I18N->msg('piwik_tracking_method'); ?></label>
              <?php echo $sel_tracking_method->show(); ?>
            </p>
          </div>
        </div>
      </fieldset>
      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-submit">
          <input class="rex-form-submit" type="submit" name="btn_save" value="<?php echo $I18N->msg('piwik_save'); ?>" />
          <input class="rex-form-submit rex-form-submit-2" type="reset" name="btn_reset" value="<?php echo $I18N->msg('piwik_reset'); ?>" />
        </p>
      </div>
    </form>
  </div>
</div>
