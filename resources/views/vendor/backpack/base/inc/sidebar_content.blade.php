<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li>
    <a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i>
        <span>{{ trans('backpack::base.dashboard') }}</span>
    </a>
</li>
<li>
    <a href="{{ backpack_url('art') }}"><i class="fa fa-photo"></i>
        <span>Arts</span>
    </a>
</li>
<li>
    <a href="{{ backpack_url('category') }}"><i class="fa fa-tags"></i>
        <span>Categories</span>
    </a>
</li>
<li>
    <a href="{{ backpack_url('paintingsize') }}"><i class="fa fa-newspaper-o"></i>
        <span>Painting Sizes</span>
    </a>
</li>
<li>
    <a href="{{ backpack_url('user') }}"><i class="fa fa-user"></i>
        <span>Users</span>
    </a>
</li>
<!-- <li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>
<li><a href="{{backpack_url('video') }}"><i class="fa fa-list"></i> <span>Videos</span></a></li>
<li><a href="{{backpack_url('videoreport') }}"><i class="fa fa-list"></i> <span>Video Reports</span></a></li>
<li><a href="{{backpack_url('page') }}"><i class="fa fa-file-o"></i> <span>Pages</span></a></li> -->
<!-- Users, Roles Permissions -->
 <!--  <li class="treeview">
    <a href="#"><i class="fa fa-group"></i> <span>Users</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">

      <li><a href="{{ backpack_url('role') }}"><i class="fa fa-group"></i> <span>Roles</span></a></li>
      <li><a href="{{ backpack_url('permission') }}"><i class="fa fa-key"></i> <span>Permissions</span></a></li>
    </ul>
  </li>
  <li><a href="{{ backpack_url('menu-item') }}"><i class="fa fa-list"></i> <span>Menu</span></a></li>
  <li class="treeview">
    <a href="#"><i class="fa fa-newspaper-o"></i> <span>News</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
      <li><a href="{{ backpack_url('article') }}"><i class="fa fa-newspaper-o"></i> <span>Articles</span></a></li>
      <li><a href="{{ backpack_url('category') }}"><i class="fa fa-list"></i> <span>Categories</span></a></li>
      <li><a href="{{ backpack_url('tag') }}"><i class="fa fa-tag"></i> <span>Tags</span></a></li>
    </ul>
</li>

<li><a href='{{ url(config('backpack.base.route_prefix', 'admin') . '/setting') }}'><i class='fa fa-cog'></i> <span>Settings</span></a></li> -->