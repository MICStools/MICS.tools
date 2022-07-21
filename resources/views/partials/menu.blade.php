<aside class="main-sidebar">
    <section class="sidebar" style="height: auto;">
        <ul class="sidebar-menu tree" data-widget="tree">
            <li>
                <a href="{{ route("admin.home") }}">
                    <i class="fas fa-fw fa-tachometer-alt">

                    </i>
                    {{ trans('global.dashboard') }}
                </a>
            </li>
            @can('user_management_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-users">

                        </i>
                        <span>{{ trans('cruds.userManagement.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('permission_access')
                            <li class="{{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}">
                                <a href="{{ route("admin.permissions.index") }}">
                                    <i class="fa-fw fas fa-unlock-alt">

                                    </i>
                                    <span>{{ trans('cruds.permission.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('role_access')
                            <li class="{{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}">
                                <a href="{{ route("admin.roles.index") }}">
                                    <i class="fa-fw fas fa-briefcase">

                                    </i>
                                    <span>{{ trans('cruds.role.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('user_access')
                            <li class="{{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                <a href="{{ route("admin.users.index") }}">
                                    <i class="fa-fw fas fa-user">

                                    </i>
                                    <span>{{ trans('cruds.user.title') }}</span>

                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('domain_access')
                <li class="{{ request()->is("admin/domains") || request()->is("admin/domains/*") ? "active" : "" }}">
                    <a href="{{ route("admin.domains.index") }}">
                        <i class="fa-fw fas fa-sitemap">

                        </i>
                        <span>{{ trans('cruds.domain.title') }}</span>

                    </a>
                </li>
            @endcan
            @can('question_access')
                <li class="{{ request()->is("admin/questions") || request()->is("admin/questions/*") ? "active" : "" }}">
                    <a href="{{ route("admin.questions.index") }}">
                        <i class="fa-fw fas fa-question">

                        </i>
                        <span>{{ trans('cruds.question.title') }}</span>

                    </a>
                </li>
            @endcan
            @can('answer_access')
                <li class="{{ request()->is("admin/answers") || request()->is("admin/answers/*") ? "active" : "" }}">
                    <a href="{{ route("admin.answers.index") }}">
                        <i class="fa-fw far fa-question-circle">

                        </i>
                        <span>{{ trans('cruds.answer.title') }}</span>

                    </a>
                </li>
            @endcan
            @can('topic_access')
                <li class="{{ request()->is("admin/topics") || request()->is("admin/topics/*") ? "active" : "" }}">
                    <a href="{{ route("admin.topics.index") }}">
                        <i class="fa-fw fas fa-tags">

                        </i>
                        <span>{{ trans('cruds.topic.title') }}</span>

                    </a>
                </li>
            @endcan
            @can('project_access')
                <li class="{{ request()->is("admin/projects") || request()->is("admin/projects/*") ? "active" : "" }}">
                    <a href="{{ route("admin.projects.index") }}">
                        <i class="fa-fw fas fa-globe">

                        </i>
                        <span>{{ trans('cruds.project.title') }}</span>

                    </a>
                </li>
            @endcan
            @can('recommendation_access')
                <li class="{{ request()->is("admin/recommendations") || request()->is("admin/recommendations/*") ? "active" : "" }}">
                    <a href="{{ route("admin.recommendations.index") }}">
                        <i class="fa-fw fas fa-cogs">

                        </i>
                        <span>{{ trans('cruds.recommendation.title') }}</span>

                    </a>
                </li>
            @endcan
            @can('country_access')
                <li class="{{ request()->is("admin/countries") || request()->is("admin/countries/*") ? "active" : "" }}">
                    <a href="{{ route("admin.countries.index") }}">
                        <i class="fa-fw fas fa-flag">

                        </i>
                        <span>{{ trans('cruds.country.title') }}</span>

                    </a>
                </li>
            @endcan
            @can('blocklist_access')
                <li class="{{ request()->is("admin/blocklists") || request()->is("admin/blocklists/*") ? "active" : "" }}">
                    <a href="{{ route("admin.blocklists.index") }}">
                        <i class="fa-fw fas fa-not-equal">

                        </i>
                        <span>{{ trans('cruds.blocklist.title') }}</span>

                    </a>
                </li>
            @endcan
            @can('result_access')
                <li class="{{ request()->is("admin/results") || request()->is("admin/results/*") ? "active" : "" }}">
                    <a href="{{ route("admin.results.index") }}">
                        <i class="fa-fw fas fa-star-half-alt">

                        </i>
                        <span>{{ trans('cruds.result.title') }}</span>

                    </a>
                </li>
            @endcan
            @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                @can('profile_password_edit')
                    <li class="{{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}">
                        <a href="{{ route('profile.password.edit') }}">
                            <i class="fa-fw fas fa-key">
                            </i>
                            {{ trans('global.change_password') }}
                        </a>
                    </li>
                @endcan
            @endif
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
        </ul>
    </section>
</aside>