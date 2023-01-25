	@if($entry->status)
	<a href="{{ url($crud->route.'/reject/'.$entry->getKey()) }}" class="btn btn-xs btn-default"><i class="fa fa-xmark"></i> Reject</a>
	@else
	<a href="{{ url($crud->route.'/approve/'.$entry->getKey()) }}" class="btn btn-xs btn-default"><i class="fa fa fa-check"></i> Approve</a>

	@endif
	