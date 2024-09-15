<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/jquery.min.js"></script>
    <title>Document</title>
</head>

<body>
    <p>Pdf to Base64String</p>
    <form role='form' method="post" name="form1" enctype="multipart/form-data" id="form1" onsubmit="return convert(event);">
        <input type="file" name="fileToUpload" id="fileToUpload" accept=".pdf">
        <input type="submit" name="submit" value="Submit" />
    </form>
    <br>
    <textarea name="Base64string" id="Base64string"></textarea>
    <button onclick="copy();">Copy</button>
</body>

</html>
<script>
    function convert(event) {
        event.preventDefault(); // ป้องกันการ submit ฟอร์มตามปกติ

        var formData = new FormData();
        var fileInput = $('#fileToUpload')[0];

        if (fileInput.files.length > 0) {
            formData.append('fileToUpload', fileInput.files[0]);

            $.ajax({
                type: 'POST',
                data: formData,
                url: 'PdfTOBase64string.php',
                contentType: false, // ควรตั้งค่าเป็น false เมื่อใช้ FormData
                processData: false, // ควรตั้งค่าเป็น false เมื่อใช้ FormData
                success: function(data) {
                    console.log("Response from PHP: ", data); // ตรวจสอบข้อมูลที่ได้รับจาก PHP
                    $("#Base64string").val(data); // แสดงข้อมูล Base64 ใน textarea
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error during AJAX request: ", textStatus, errorThrown); // ตรวจสอบ error หากมีปัญหา
                }
            });
        } else {
            console.log("No file selected.");
        }

        return false; // คืนค่า false เพื่อหยุดการส่งฟอร์ม
    }
    const copy = () => {
        $("button").click(function() {
            $("#Base64string").select();
            document.execCommand('copy');
        });
    }
</script>