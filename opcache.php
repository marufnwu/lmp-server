<?php
phpinfo();
 if((is_array(opcache_get_status()))){
echo 'enabled';
}else{
    echo 'disabled';
} ?>