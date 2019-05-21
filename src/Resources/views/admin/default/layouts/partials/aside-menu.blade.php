<li class="nav-label">Manage</li>
<li class="nav-item with-sub"><a href="dashboard-one.html" class="nav-link">
        <i data-feather="users"></i> <span>Users & Access</span>
    </a>

    <ul>
        <li><a href="page-profile-view.html">Users</a></li>
        <li><a href="page-profile-view.html">Roles</a></li>
        <li><a href="page-connections.html">Permissions</a></li>
    </ul>

</li>

<li class="nav-item with-sub">
    <a href="dashboard-one.html" class="nav-link">
        <i data-feather="package"></i> <span>Extend</span>
    </a>

    <ul>
        <li><a href="{{url()->route('vh.admin.modules')}}">Modules (3)</a></li>
        <li><a href="page-profile-view.html">Plugins  (4)</a></li>
        <li><a href="page-profile-view.html">Widgets</a></li>
        <li><a href="page-profile-view.html">Themes</a></li>
    </ul>

</li>

<li class="nav-item with-sub">
    <a href="#" class="nav-link">
        <i data-feather="settings"></i> <span>Settings</span>
    </a>

    <ul>
        <li><a href="{{url()->route("vh.admin.vaahcms.settings")}}">VaahCMS</a></li>
        @include("vaahcms::admin.default.extend.settings-menu")
    </ul>

</li>

<li class="nav-label  mg-t-25">Content</li>

<li class="nav-item"><a href="dashboard-one.html" class="nav-link">
        <i data-feather="edit-2"></i> <span>Posts</span>
    </a>
</li>

<li class="nav-item"><a href="dashboard-one.html" class="nav-link">
        <i data-feather="file-text"></i> <span>Pages</span>
    </a>
</li>

<li class="nav-item"><a href="dashboard-one.html" class="nav-link">
        <i data-feather="image"></i> <span>Media</span>
    </a>
</li>

<li class="nav-item"><a href="dashboard-one.html" class="nav-link">
        <i data-feather="message-square"></i> <span>Comments</span>
    </a>
</li>

<li class="nav-item"><a href="dashboard-one.html" class="nav-link">
        <i data-feather="tag"></i> <span>Tags</span>
    </a>
</li>


@include("vaahcms::admin.default.extend.aside-menu")