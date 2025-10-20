<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Import Questions from JSON</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: #0f172a !important;
        color: #e2e8f0 !important;
    }
    
    .container {
        background-color: #1e293b;
        padding: 30px;
        border-radius: 15px;
        border: 1px solid #475569;
        margin-top: 50px;
    }
    
    h2 {
        color: #e2e8f0 !important;
        margin-bottom: 20px;
    }
    
    label {
        color: #e2e8f0 !important;
    }
    
    .form-control, .form-select {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
        border: 1px solid #475569 !important;
    }
    
    .form-control:focus, .form-select:focus {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
        border-color: #15803d !important;
        box-shadow: 0 0 0 0.2rem rgba(21, 128, 61, 0.25) !important;
    }
    
    .btn-primary {
        background-color: #15803d !important;
        border-color: #15803d !important;
        color: white !important;
        box-shadow: none !important;
    }
    
    .btn-primary:hover {
        background-color: #166534 !important;
        border-color: #166534 !important;
    }
    
    .alert-success {
        background-color: #15803d !important;
        border-color: #15803d !important;
        color: white !important;
    }
    
    .alert-danger {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
        color: white !important;
    }
</style>
</head>
<body>
<div class="container mt-5">
    <h2>Import Questions from JSON</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('questions.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>JSON File</label>
            <input type="file" name="json_file" class="form-control" accept=".json" required>
        </div>

        <div class="mb-3">
            <label>Class (optional)</label>
            <select name="class" class="form-control" id="classSelect" >
                <option value="">--Select Class--</option>
                <option value="SSC">SSC</option>
                <option value="HSC">HSC</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Subject (optional)</label>
            <select name="subject_id" class="form-control" id="subjectSelect">
                <option value="">--Select Subject--</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Chapter (optional)</label>
            <select name="chapter_id" class="form-control" id="chapterSelect">
                <option value="">--Select Chapter--</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Source Name (optional)</label>
            <input type="text" name="source_name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Source Type (optional)</label>
            <input type="text" name="source_type" class="form-control" value = "Board">
        </div>

        <div class="mb-3">
            <label>Year (optional)</label>
            <input type="number" name="year" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Import Questions</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('classSelect');
    const subjectSelect = document.getElementById('subjectSelect');
    const chapterSelect = document.getElementById('chapterSelect');

    classSelect.addEventListener('change', function(){
        subjectSelect.innerHTML = '<option value="">--Select Subject--</option>';
        chapterSelect.innerHTML = '<option value="">--Select Chapter--</option>';
        const classVal = this.value;
        if(classVal){
            fetch(`/questions/get-subjects?class=${classVal}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(sub => {
                    const opt = document.createElement('option');
                    opt.value = sub.id;
                    opt.text = sub.name;
                    subjectSelect.appendChild(opt);
                });
            });
        }
    });

    subjectSelect.addEventListener('change', function(){
        chapterSelect.innerHTML = '<option value="">--Select Chapter--</option>';
        const subjectVal = this.value;
        if(subjectVal){
            fetch(`/questions/get-chapters?subject_id=${subjectVal}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(chap => {
                    const opt = document.createElement('option');
                    opt.value = chap.id;
                    opt.text = chap.name;
                    chapterSelect.appendChild(opt);
                });
            });
        }
    });
});
</script>

</body>
</html>
