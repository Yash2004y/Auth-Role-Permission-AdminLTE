<div class="modal fade ajaxModal" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('permissions.store') }}" method="POST" class="modal-content ajaxForm">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="permissionModalLabel">Permission {{ $type }}</h1>
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <input type="hidden" value="{{ $permission?->id }}" name="id" />
                <div class="row">
                    <div class="col-12">
                        <input class="form-control" name="permission" value="{{ $permission?->name }}"
                            placeholder="Permission name" />
                        <span class="text-danger permission-error error-common"></span>

                    </div>
                    <div class="col-12 mt-2">
                        <textarea class="form-control" name="description" placeholder="Permission description">{{ $permission?->description }}</textarea>
                        <span class="text-danger description-error error-common"></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </form>
    </div>
</div>
