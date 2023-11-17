<?php

namespace App\Core;

use App\Core\Adapters\Theme;
use App\Models\SystemUserGroup;
use App\Models\SystemMenu;
use App\Models\SystemMenuMapping;
// use App\Core\Adapters\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class Menu {
    // Menu config
    public $items;

    // Current page path
    private $path;

    // Default menu tag
    private $parentTag = 'div';

    // Default menu item tag
    private $itemTag = 'div';

    // Default icon visibility
    private $displayIcons = true;

    // Hide root level icons
    private $hideRootLevelIcons = false;

    // Menu registered callbacks
    private $callbacks = array();

    // Default item link class
    private $itemLinkClass = '';

    // Default icon type in menu config
    private $iconType = 'svg';

    /**
     * Private Methods.
     *
    */
    private function _generateItem($item, $level = 0) {
        $classes = array('menu-item');
        $attributes = array();

        // Overcome recursive infinite loop
        if ( $level > 10000 ) {
            return;
        }

        // Process callable item
        if (is_callable($item)) {
            $item = call_user_func($item);
        }

        // Exit if item is null
        if ($item === null) {
            return;
        }

        // Handle menu item visiblity with callback function
        if (isset($item['hide'])) {
            if (is_callable($item['hide'])) {
                $hide = call_user_func($item['hide'], $this, $item);
            } else {
                $hide = $item['hide'];
            }

            if ($hide === true) {
                return;
            }
        }

        if ( isset($item['sub']) && ($this->_matchParentItemByPath($item) === true)) {
            $classes[] = 'here show';
        }

        if ( isset($item['attributes']) && isset($item['attributes']['item']) ) {
            $attributes = $item['attributes']['item'];
        } elseif ( isset($item['attributes']) && isset($item['attributes']['link']) === false ) {
            $attributes = $item['attributes'];
        }

        if ( isset($item['classes']) && isset($item['classes']['item']) ) {
            $classes[] = $item['classes']['item'];
        }

        echo '<' . $this->itemTag . ' ' . Util::getHtmlAttributes($attributes) . Util::getHtmlClass($classes) . '>';

        if ( isset($item['custom']) ) {
            $this->_generateItemCustom($item);
        }

        if ( isset($item['content']) ) {
            $this->_generateItemContent($item);
        }

        if ( isset($item['title']) || isset($item['breadcrumb-title']) ) {
            $this->_generateItemLink($item);
        }

        if ( isset($item['heading']) ) {
            $this->_generateItemHeading($item);
        }

        if ( isset($item['sub']) ) {
            $this->_generateItemSub($item['sub'], $level++);
        }

        echo '</' . $this->itemTag . '>';
    }

    private function _generateItemLink($item) {
        $classes = array('menu-link');
        $attributes = array();
        $tag = 'a';
        // Construct li ks attributes
        if ( isset($item['path']) ) {
            // Assign the page URL
            $attributes['href'] = Theme::getPageUrl($item['path']);

            // Handle open in new tab mode
            if (isset($item['new-tab']) && $item['new-tab'] === true) {
                $attributes['target'] = 'blank';
            }

            // Add special attribute for links to pro pages
            if (Theme::isFreeVersion() === true && Theme::isProPage($item['path']) === true) {
                $attributes['data-kt-page'] = 'pro';
            }
        } else {
            $tag = 'span';
        }

        if ( isset($item['attributes']) && isset($item['attributes']['link']) ) {
            $attributes = array_merge($attributes, $item['attributes']['link']);
        }

        if ( $this->_matchItemByPath($item) === true ) {
            $classes[] = 'active';
        }

        if (!empty($this->itemLinkClass)) {
            $classes[] = $this->itemLinkClass;
        }

        if ( isset($item['classes']) && isset($item['classes']['link']) ) {
            $classes[] = $item['classes']['link'];
        }

        echo '<' . $tag . Util::getHtmlClass($classes) .  Util::getHtmlAttributes($attributes) . '>';

        if ($this->displayIcons !== false) {
            $this->_generateItemLinkIcon($item);
        }

        $this->_generateItemLinkBullet($item);

        if ( isset($item['title']) ) {
            $this->_generateItemLinkTitle($item);
        }

        $this->_generateItemLinkBadge($item);

        if ( isset($item['sub']) && @$item['arrow'] !== false ) {
            $this->_generateItemLinkArrow($item);
        }

        echo '</' . $tag . '>';
    }

    private function _generateItemLinkTitle($item) {
        $classes = array('menu-title');

        if ( isset($item['classes']) && isset($item['classes']['title']) ) {
            $classes[] = $item['classes']['title'];
        }

        if (!is_string($item['title']) && is_callable($item['title'])) {
            $item['title'] = call_user_func($item['title'], $item);
        }

        echo '<span ' . Util::getHtmlClass($classes) . '>';

        if ( isset($this->callbacks['title']) && is_callable($this->callbacks['title']) ) {
            echo call_user_func($this->callbacks['title'], $item, $item['title']);
        } else {
            echo $item['title'];
            // Append exclusive badge
            if (isset($item['path']) && Theme::isExclusivePage($item['path']) === true) {
                echo '<span class="badge badge-exclusive badge-light-success fw-bold fs-9 px-2 py-1 ms-1">Exclusive</span>';
            }

            // Append pro badge
			if (Theme::isFreeVersion()) {
				if ((isset($item['path']) && Theme::isProPage($item['path']) === true) || (isset($item['pro']) && $item['pro'] === true)) {
					echo '<span class="badge badge-pro badge-light-danger fw-bold fs-9 px-2 py-1 ms-1">Pro</span>';
				}
			}
        }

        echo '</span>';
    }

    private function _generateItemLinkIcon($item) {
        $classes = array('menu-icon');

        if ( isset($item['classes']) && isset($item['classes']['icon']) ) {
            $classes[] = $item['classes']['icon'];
        }

        if ( isset($item['icon']) ) {
            echo '<span ' . Util::getHtmlClass($classes) . '>';

            if (is_array($item['icon'])) {
                echo $item['icon'][$this->iconType];
            } else {
                echo $item['icon'];
            }

            echo '</span>';
        }
    }

    private function _generateItemLinkBullet($item) {
        if (isset($item['icon']) === true && $this->displayIcons !== false) {
            return;
        }

        $classes = array('menu-bullet');

        if ( isset($item['classes']) && isset($item['classes']['bullet']) ) {
            $classes[] = $item['classes']['bullet'];
        }

        if ( isset($item['bullet']) ) {
            echo '<span ' . Util::getHtmlClass($classes) . '>';

            if ( isset($item['bullet'])) {
                echo $item['bullet'];
            }

            echo '</span>';
        }
    }

    private function _generateItemLinkBadge($item) {
        $classes = array('menu-badge');

        if ( isset($item['classes']) && isset($item['classes']['badge']) ) {
            $classes[] = $item['classes']['badge'];
        }

        if ( isset($item['badge']) ) {
            echo '<span ' . Util::getHtmlClass($classes) . '>';
            echo $item['badge'];
            echo '</span>';
        }
    }

    private function _generateItemLinkArrow($item) {
        $classes = array('menu-arrow');

        if ( isset($item['classes']['arrow']) ) {
            $classes[] = $item['classes']['arrow'];
        }

        echo '<span ' . Util::getHtmlClass($classes) . '>';
        echo '</span>';
    }

    private function _generateItemSub($sub, $level) {
        $classes = array('menu-sub');

        if ( isset($sub['class']) ) {
            $classes[] = $sub['class'];
        }

        echo '<' . $this->parentTag . ' ' . Util::getHtmlClass($classes) . '>';

        if (isset($sub['view'])) {
            Theme::getView($sub['view']);
        } else {
            foreach ($sub['items'] as $item) {
                $this->_generateItem($item, $level++);
            }
        }

        echo '</' . $this->parentTag . '>';
    }

    private function _generateItemHeading($item) {
        $classes = array('menu-content');

        if ( isset($item['heading']) ) {
            if ( isset($this->callbacks['heading']) && is_callable($this->callbacks['heading']) ) {
                echo call_user_func($this->callbacks['heading'], $item['heading']);
            } else {
                echo '<h3 ' . Util::getHtmlClass($classes) . '>';
                echo $item['heading'];
                echo '</h3>';
            }
        }
    }

    private function _generateItemContent($item) {
        $classes = array('menu-content');

        if ( isset($item['classes']) && isset($item['classes']['content']) ) {
            $classes[] = $item['classes']['content'];
        }

        if ( isset($item['content']) ) {
            echo '<div ' . Util::getHtmlClass($classes) . '>';
            echo $item['content'];
            echo '</div>';
        }
    }

    private function _generateItemCustom($item) {
        if ( isset($item['custom']) ) {
            echo $item['custom'];
        }
    }

    private function _matchParentItemByPath($item, $level = 0) {
        if ( $level > 1000 ) {
            return false;
        }

        if ( $this->_matchItemByPath($item) === true ) {
            return true;
        } else {
            if ( isset($item['sub']) && isset($item['sub']['items']) ) {
                foreach ( $item['sub']['items'] as $currentItem) {
                    if ( $this->_matchParentItemByPath($currentItem, $level++) === true) {
                        return true;
                    }
                }
            }

            return false;
        }
    }

    private function _matchItemByPath($item) {
        // if ( isset($item['path']) && ($this->path === $item['path'] || $this->path === $item['path'] . '/index') ) {
        if(str_contains($this->path, '/') == true){
            if ( isset($item['path']) && str_starts_with($this->path, $item['path'].'/') == true) {
                return true;
            } else {
                return false;
            }
        }else{
            if ( isset($item['path']) && ($this->path === $item['path'] || $this->path === $item['path'] . '/index')) {
                return true;
            } else {
                return false;
            }
        }
    }

    private function _buildBreadcrumb($items, &$breadcrumb, $options, $level = 0) {
        if ( $level > 10000 ) {
            return false;
        }

        foreach ( $items as $item ) {
            $title = '';

            if (isset($item['breadcrumb-title'])) {
                $title = $item['breadcrumb-title'];
            } else if (isset($item['title'])) {
                if (!is_string($item['title']) && is_callable($item['title'])) {
                    $title = call_user_func($item['title'], $item);
                } else {
                    $title = $item['title'];
                }
            } else if (isset($item['heading'])) {
                $title = $item['heading'];
            }

            if ( isset($item['path']) && ($item['path'] === $this->path || $item['path'] . '/index' === $this->path)) {
                if (@$options['skip-active'] !== true) {
                    $breadcrumb[] = array(
                        'title' => $title,
                        'path' => isset($item['path']) ? $item['path'] : '',
                        'active' => true
                    );
                }

                return true;

            } else if ( isset($item['sub']) && isset($item['sub']['items']) ) {
                if ( $this->_buildBreadcrumb($item['sub']['items'], $breadcrumb, $options, $level++) === true) {
                    $breadcrumb[] = array(
                        'title' => $title,
                        'path' => isset($item['path']) ? $item['path'] : ( isset($item['alt-path']) ? $item['alt-path'] : ''),
                        'active' => false
                    );

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Public Methods.
     *
    */
    public function __construct($items, $path = '') {
        $this->items = $items;
        $this->path = $path;

        return $this;
    }

    /**
     * options: array('includeHomeLink' => boolean, 'homeLink' => array(), 'skipCurrentPage' => boolean)
     *
     */
    public function getBreadcrumb($options = array()) {
        $breadcrumb = array();

        //$includeHomeLink = true, $homeLink = null

        $this->_buildBreadcrumb($this->items, $breadcrumb, $options);

        $breadcrumb = array_reverse($breadcrumb, true);

        if ( !empty($breadcrumb)) {
            if ( isset($options['home']) ) {
                array_unshift($breadcrumb, $options['home']);
            } else {
                array_unshift($breadcrumb, array(
                    'title' => 'Home',
                    'path' => 'index',
                    'active' => false
                ));
            }
        }

		return $breadcrumb;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function displayIcons($flag) {
        $this->displayIcons = $flag;
    }

    public function hideRootLevelIcons($flag) {
        $this->hideRootLevelIcons = $flag;
    }

    public function setItemTag($tagName) {
        $this->itemTag = $tagName;
    }

    public function setIconType($type) {
        $this->iconType = $type;
    }

    public function setItemLinkClass($class) {
        $this->itemLinkClass = $class;
    }

    public function addCallback($target, $callback) {
        if ( !is_string($callback) && is_callable($callback) ) {
            $this->callbacks[$target] = $callback;
        }
    }

    public function build() {
        $user   = auth()->user();
        $items  = array();
        $menus  = SystemMenu::select('*')
        ->where('parent', '#')
        ->where('type', '!=', 'function')
        ->get();
        foreach($menus as $key => $val){
            if(strlen($val['id_menu']) == 1){
                //*ROLE MENU 1
                // $role           = array();
                // $menu_mapping   = SystemMenuMapping::select('system_user_group.user_group_name')
                // ->join('system_user_group', 'system_user_group.user_group_level', 'system_menu_mapping.user_group_level')
                // ->where('system_menu_mapping.id_menu', $val['id_menu'])
                // ->get();
                // foreach($menu_mapping as $role_key => $role_val){
                //     array_push($role, $role_val['user_group_name']);
                // }

                //*MENU 2
                $sub            = array();
                $childmenus     = SystemMenu::select('*')
                ->where('parent', $val['id_menu'])
                ->where('type', '!=', 'function')
                ->get();
                foreach($childmenus as $keyy => $vall){
                    //*ROLE MENU 2
                    // $rolesub        = array();
                    // $menu_mapping   = SystemMenuMapping::select('system_user_group.user_group_name')
                    // ->join('system_user_group', 'system_user_group.user_group_level', 'system_menu_mapping.user_group_level')
                    // ->where('system_menu_mapping.id_menu', $vall['id_menu'])
                    // ->get();
                    
                    // foreach($menu_mapping as $role_key => $role_val){
                    //     array_push($rolesub, $role_val['user_group_name']);
                    // }

                    //*MENU 3
                    $subsub             = array();
                    $childchildmenus    = SystemMenu::select('*')
                    ->where('parent', $vall['id_menu'])
                    ->where('type', '!=', 'function')
                    ->get();
                    foreach($childchildmenus as $keyyy => $valll){
                        //*ROLE MENU 3
                        // $rolesubsub     = array();
                        // $menu_mapping   = SystemMenuMapping::select('system_user_group.user_group_name')
                        // ->join('system_user_group', 'system_user_group.user_group_level', 'system_menu_mapping.user_group_level')
                        // ->where('system_menu_mapping.id_menu', $valll['id_menu'])
                        // ->get();
                        // foreach($menu_mapping as $role_key => $role_val){
                        //     array_push($rolesubsub, $role_val['user_group_name']);
                        // }

                        $new_sub = array(
                            'title'   => $valll['text'],
                            'bullet'  => '<span class="bullet bullet-dot"></span>',
                            'path'    => $valll['id'],
                            'classes' => array('item' => 'me-lg-1'),
                            // 'role'    => $rolesub,
                        );
                        $cek_mapping = null;
                        $cek_mapping = SystemMenuMapping::select('system_menu_mapping.*')
                        ->join('system_user_group', 'system_user_group.user_group_level', 'system_menu_mapping.user_group_level')
                        ->where('system_menu_mapping.id_menu', $valll['id_menu'])
                        ->where('system_user_group.user_group_id', $user['user_group_id'])
                        ->first();
                        if($cek_mapping){
                            array_push($subsub, $new_sub);
                        }
                    }

                    //*ASSIGN MENU 2
                    if(count($subsub) == 0){
                        $new_sub = array(
                            'title'   => $vall['text'],
                            'bullet'  => '<span class="bullet bullet-dot"></span>',
                            'path'    => $vall['id'],
                            'classes' => array('item' => 'me-lg-1'),
                            // 'role'    => $rolesub,
                        );
                    }else{
                        $new_sub = array(
                            'title'   => $vall['text'],
                            'bullet'  => '<span class="bullet bullet-dot"></span>',
                            'classes'    => array('item' => 'menu-lg-down-accordion me-lg-1', 'arrow' => 'd-lg-right'),
                            'attributes' => array(
                                'data-kt-menu-trigger'   => "{default: 'click', lg: 'hover'}",
                                'data-kt-menu-placement' => "right-start",
                            ),
                            // 'role'    => $rolesub,
                            'sub'     => array(
                                'class' => 'menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px',
                                'items' => $subsub,
                            ),
                        );
                    }
                    $cek_mapping = null;
                    $cek_mapping = SystemMenuMapping::select('system_menu_mapping.*')
                    ->join('system_user_group', 'system_user_group.user_group_level', 'system_menu_mapping.user_group_level')
                    ->where('system_menu_mapping.id_menu', $vall['id_menu'])
                    ->where('system_user_group.user_group_id', $user['user_group_id'])
                    ->first();
                    if($cek_mapping){
                        array_push($sub, $new_sub);
                    }
                }
                //*ASSIGN MENU 1
                if(count($sub) == 0){
                    $menu = array(
                        'title'   => $val['text'],
                        'path'    => $val['id'],
                        'classes' => array('item' => 'me-lg-1'),
                        // 'role'    => $role,
                    );
                }else{
                    $menu = array(
                        'title'   => $val['text'],
                        'classes'    => array('item' => 'menu-lg-down-accordion me-lg-1', 'arrow' => 'd-lg-none'),
                        'attributes' => array(
                            'data-kt-menu-trigger'   => "{default: 'click', lg: 'hover'}",
                            'data-kt-menu-placement' => "bottom-start",
                        ),
                        // 'role'    => $role,
                        'sub'     => array(
                            'class' => 'menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px',
                            'items' => $sub,
                        ),
                    );
                }
                $cek_mapping = null;
                $cek_mapping = SystemMenuMapping::select('system_menu_mapping.*')
                ->join('system_user_group', 'system_user_group.user_group_level', 'system_menu_mapping.user_group_level')
                ->where('system_menu_mapping.id_menu', $val['id_menu'])
                ->where('system_user_group.user_group_id', $user['user_group_id'])
                ->first();
                if($cek_mapping){
                    array_push($items, $menu);
                }
            }
        } 
        $this->items = $items;

        foreach ($this->items as $item) {
            $this->_generateItem($item);
        }
    }
}
