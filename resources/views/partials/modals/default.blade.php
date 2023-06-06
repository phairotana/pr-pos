<!-- Modal -->
<div class="modal fade" id="{{ $modalIdentify ?? 'modalDefault' }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog {{ $modalSize ?? 'modal-full' }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold"> {{ $title ?? 'Modal Default' }}</h5>
            </div>
            <div class="modal-body">
                {!! $body ?? '' !!}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm btn-hidden-modal" data-dismiss="modal">Close</button>
                @if(!empty($modalType) && $modalType == 'create')
                    <button type="button" class="btn btn-primary btn-sm" id="{{ $btnIdentify ?? 'btn-create' }}" style="{{ $btnRightStyle ?? '' }}">{{ $btnText ?? 'Save' }}</button>
                @endif
                @if(!empty($modalType) && $modalType == 'update')
                    <button type="button" class="btn btn-primary btn-sm" id="{{ $btnIdentify ?? 'btn-update' }}" style="{{ $btnRightStyle ?? '' }}">Update</button>
                @endif
                @if(!empty($modalType) && $modalType == 'restore')
                    <button type="button" class="btn btn-primary btn-sm" id="{{ $btnIdentify ?? 'btn-restore' }}" style="{{ $btnRightStyle ?? '' }}">Restore</button>
                @endif
            </div>
        </div>
    </div>
</div>