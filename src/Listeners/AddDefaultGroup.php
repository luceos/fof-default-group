<?php

/*
 * This file is part of fof/default-group.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\DefaultGroup\Listeners;

use Flarum\Group\Group;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\Event\Activated;

class AddDefaultGroup
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @param SettingsRepositoryInterface $settings
     */
    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function handle(Activated $event)
    {
        $defaultGroupId = $this->settings->get('fof-default-group.group');

        if (! $defaultGroupId || $defaultGroupId === Group::MEMBER_ID) {
            return;
        }

        $defaultGroup = Group::find($defaultGroupId);

        if (! $defaultGroup) {
            return;
        }

        if (! $event->users->groups->contains($defaultGroup)) {
            $event->user->groups()->attach($defaultGroupId);
        }
    }
}
