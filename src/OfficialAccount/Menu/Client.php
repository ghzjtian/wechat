<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChat\OfficialAccount\Menu;

use EasyWeChat\Kernel\BaseClient;

/**
 * Class Client.
 *
 * @author overtrue <i@overtrue.me>
 */
class Client extends BaseClient
{
    /**
     * Get all menus.
     *
     * @return mixed
     */
    public function list()
    {
        return $this->httpGet('cgi-bin/menu/get');
    }

    /**
     * Get current menus.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->httpGet('cgi-bin/get_current_selfmenu_info');
    }

    /**
     * Add menu.
     *
     * @param array $buttons
     * @param array $matchRule
     *
     * @return mixed
     */
    public function create(array $buttons, array $matchRule = [])
    {
        if (!empty($matchRule)) {
            //个性化菜单.
            return $this->httpPostJson('cgi-bin/menu/addconditional', [
                'button' => $buttons,
                'matchrule' => $matchRule,
            ]);
        }

        //普通菜单: https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141013
        $myMenuResponse = $this->httpPostJson('cgi-bin/menu/create', ['button' => $buttons]);


        return $myMenuResponse;
    }

    /**
     * Destroy menu.
     *
     * @param int $menuId
     *
     * @return mixed
     */
    public function delete(int $menuId = null)
    {
        if (is_null($menuId)) {
            return $this->httpGet('cgi-bin/menu/delete');
        }

        return $this->httpPostJson('cgi-bin/menu/delconditional', ['menuid' => $menuId]);
    }

    /**
     * Test conditional menu.
     *
     * @param string $userId
     *
     * @return mixed
     */
    public function match(string $userId)
    {
        return $this->httpPostJson('cgi-bin/menu/trymatch', ['user_id' => $userId]);
    }
}
