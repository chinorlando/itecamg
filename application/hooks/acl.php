<?php
/**
 * This class will be called by the post_controller_constructor hook and act as ACL
 * 
 * @author ChristianGaertner
 */
class ACL {
    
    /**
     * Array to hold the rules
     * Keys are the role_id and values arrays
     * In this second level arrays the key is the controller and value an array with key method and value boolean
     * @var Array 
     */
    private static $perms;
    /**
     * The field name, which holds the role_id
     * @var string 
     */
    private static $role_field;
    private static $error;
    /**
     * Contstruct in order to set rules
     * @author ChristianGaertner
     */
    public function __construct() {
        // $self = new static;
        self::$role_field = 'id_rol';
        
        
        // self::$perms[0]['login']['index']        = TRUE;
        // self::$perms[0]['login']['login']        = TRUE;
        // self::$perms[0]['login']['logout']        = TRUE;
        // self::$perms[0]['admin']['index']    = TRUE;
        self::$perms[1]['admin']['index']         = TRUE;
        self::$perms[1]['Csv_import']['index']         = TRUE;
        self::$perms[1]['persona']['index']         = TRUE;
        self::$perms[1]['admin']['programaciones']         = TRUE;
        self::$perms[1]['profile']['index']         = TRUE;
        self::$perms[1]['admin']['menu']         = TRUE;
        self::$perms[1]['admin']['docente']         = TRUE;
        self::$perms[1]['admin']['paralelo']         = TRUE;
        self::$perms[1]['admin']['gestionperiodo']         = TRUE;
        self::$perms[1]['admin']['bimestre']         = TRUE;
        self::$perms[1]['admin']['boletin']         = TRUE;
        self::$perms[1]['admin']['estadistica']         = TRUE;
        self::$perms[1]['centralizador']['index']         = TRUE; // si ponemos FALSE no podra ingresar a ese metodo
        // self::$perms[1]['admin']['index']         = TRUE;
        // self::$perms[1]['admin']['index']         = TRUE;
        // self::$perms[1]['admin']['index']         = TRUE;
        // self::$perms[1]['admin']['index']         = TRUE;
        // self::$perms[1]['admin']['index']         = TRUE;
        // self::$perms[1]['admin']['index']         = TRUE;
        // self::$perms[1]['admin']['index']         = TRUE;
        // self::$perms[1]['admin']['index']         = TRUE;
        // self::$perms[1]['admin']['index']         = TRUE;
        // self::$perms[1]['admin']['index']         = TRUE;
        // self::$perms[1]['admin']['index']         = TRUE;

        // self::$perms[0]['admin']['programaciones']    = FALSE;
        // self::$perms[1]['user']['show']         = true;
        // self::$perms[2]['admin']['dashboard']   = true;
        // self::$perms[3]['admin']['settings']    = true;
    }
    /**
     * The main method, determines if the a user is allowed to view a site
     * @author ChristianGaertner
     * @return boolean
     */
    public function auth()
    {
        $CI =& get_instance();

        $CI->load->helper(array('url', 'exceptions'));
        $CI->load->library('form_validation');
        
        if (!isset($CI->session))
        { # Sessions are not loaded
            $CI->load->library('session');
        }
        
        if (!isset($CI->router))
        { # Router is not loaded
            $CI->load->library('router');
        }
        
        
        $class = $CI->router->fetch_class();
        $method = $CI->router->fetch_method();

        // Is rule defined?
        $is_ruled = false;
        
        foreach (self::$perms as $role)
        { # Loop through all rules
            if (isset($role[$class][$method]))
            { # For this role exists a rule for this route
                $is_ruled = true;
            }
        }
        if (!$is_ruled)
        { # No rule defined for this route
            // ignording the ACL
            return;
        }
        // print_r(self::$role_field);
        // print_r($CI->session->userdata(self::$role_field));
        // exit();
        
        if ($CI->session->userdata(self::$role_field))
        { # Role_ID successfully determined ==> User is logged in
            if (self::$perms[$CI->session->userdata(self::$role_field)][$class][$method])
            // if (self::$perms[1][$class][$method])
            { # The user is allowed to enter the site
                return true;
            }
            else
            { # The user does not have permissions
                // $clase = $CI->router->fetch_class();
                // $CI->error();
                show_403();
            }
        }
        else
        { # not logged in
            if (self::$perms[1][$class][$method])
            { # The user is allowed to enter the site
                return true;
            }
            else
            { # The user does not have permissions
                // $CI->error();
                show_403();
            }
        }
        
        
    }
}