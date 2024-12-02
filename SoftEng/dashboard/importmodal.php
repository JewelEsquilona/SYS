<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Alumni Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="importdata.php" method="post" enctype="multipart/form-data"> 
                    <div class="mb-3">
                        <label for="file" class="form-label">Choose CSV file</label>
                        <input type="file" name="file" class="form-control" required />
                        <p class="text-start mb-0 mt-2">
                            <a href="sample_format.csv" class="link-primary" download>Download Sample Format</a>
                        </p>
                    </div>
                    <button type="submit" class="btn btn-success" name="importSubmit">Import CSV</button>
                </form>
            </div>
        </div>
    </div>
</div>
