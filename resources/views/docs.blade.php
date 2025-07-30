<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laravel JWT Authentication & CRUD API Documentation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f8f9fa;
            color: #333;
        }

        header {
            background: #343a40;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin: 0;
            font-size: 24px;
        }

        h2 {
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            color: #007bff;
            margin-top: 40px;
        }

        h3 {
            margin-top: 30px;
            color: #495057;
        }

        section {
            background: #fff;
            padding: 20px;
            margin: 20px auto;
            max-width: 1100px;
            border-radius: 6px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 14px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        th {
            background: #f1f3f5;
        }

        code {
            background: #f1f3f5;
            padding: 2px 5px;
            border-radius: 3px;
        }

        pre {
            background: #212529;
            color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 13px;
        }

        .method {
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .POST {
            background: #28a745;
            color: #fff;
        }

        .GET {
            background: #007bff;
            color: #fff;
        }

        .PUT,
        .PATCH {
            background: #ffc107;
            color: #000;
        }

        .DELETE {
            background: #dc3545;
            color: #fff;
        }

        .optional {
            color: #6c757d;
        }

        .required {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <header>
        <h1>Laravel JWT Authentication & CRUD API Documentation</h1>
    </header>

    <main>

        <!-- ==================== AUTHENTICATION ==================== -->
        <section>
            <h2>Authentication APIs</h2>

            <!-- Register -->
            <h3>1. Register User</h3>
            <p><span class="method POST">POST</span> <code>/api/register</code></p>
            <h4>Headers</h4>
            <table>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Required</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>Accept</td>
                    <td>application/json</td>
                    <td class="required">Yes</td>
                    <td>Response will be JSON</td>
                </tr>
                <tr>
                    <td>Content-Type</td>
                    <td>application/json</td>
                    <td class="required">Yes</td>
                    <td>JSON request body</td>
                </tr>
            </table>
            <h4>Request Payload</h4>
            <table>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Validation Rules</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>name</td>
                    <td>string</td>
                    <td class="required">Yes</td>
                    <td>max:255</td>
                    <td>Full name</td>
                </tr>
                <tr>
                    <td>email</td>
                    <td>string</td>
                    <td class="required">Yes</td>
                    <td>valid email, unique</td>
                    <td>User email</td>
                </tr>
                <tr>
                    <td>password</td>
                    <td>string</td>
                    <td class="required">Yes</td>
                    <td>min:6</td>
                    <td>Password</td>
                </tr>
                <tr>
                    <td>password_confirmation</td>
                    <td>string</td>
                    <td class="required">Yes</td>
                    <td>same as password</td>
                    <td>Password confirmation</td>
                </tr>
            </table>
            <pre>{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}</pre>
            <pre>{
  "token": "eyJ0eXAiOiJKV1QiLCJh...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}</pre>

            <!-- Login -->
            <h3>2. Login User</h3>
            <p><span class="method POST">POST</span> <code>/api/login</code></p>
            <h4>Headers</h4>
            <table>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Required</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>Accept</td>
                    <td>application/json</td>
                    <td class="required">Yes</td>
                    <td>Response will be JSON</td>
                </tr>
                <tr>
                    <td>Content-Type</td>
                    <td>application/json</td>
                    <td class="required">Yes</td>
                    <td>JSON request body</td>
                </tr>
            </table>
            <h4>Request Payload</h4>
            <table>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Validation Rules</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>email</td>
                    <td>string</td>
                    <td class="required">Yes</td>
                    <td>valid email</td>
                    <td>User email</td>
                </tr>
                <tr>
                    <td>password</td>
                    <td>string</td>
                    <td class="required">Yes</td>
                    <td>min:6</td>
                    <td>Password</td>
                </tr>
            </table>
            <pre>{
  "email": "john@example.com",
  "password": "password123"
}</pre>
            <pre>{
  "token": "eyJ0eXAiOiJKV1QiLCJh...",
  "expires_in": 900
}</pre>

            <!-- Refresh Token -->
            <h3>3. Refresh Token</h3>
            <p><span class="method POST">POST</span> <code>/api/refreshToken</code></p>
            <h4>Headers</h4>
            <table>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Required</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>Authorization</td>
                    <td>Bearer &lt;JWT_TOKEN&gt;</td>
                    <td class="required">Yes</td>
                    <td>Valid token</td>
                </tr>
                <tr>
                    <td>Accept</td>
                    <td>application/json</td>
                    <td class="required">Yes</td>
                    <td>JSON response</td>
                </tr>
            </table>
            <pre>{
  "status": true,
  "message": "Token refreshed successfully",
  "token": "new.jwt.token...",
  "expires_in": 7200
}</pre>

            <!-- Get Current User -->
            <h3>4. Get Current User</h3>
            <p><span class="method GET">GET</span> <code>/api/user</code></p>
            <h4>Headers</h4>
            <table>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Required</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>Authorization</td>
                    <td>Bearer &lt;JWT_TOKEN&gt;</td>
                    <td class="required">Yes</td>
                    <td>Valid token</td>
                </tr>
            </table>
            <pre>{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com"
}</pre>

            <!-- Update User -->
            <h3>5. Update User</h3>
            <p><span class="method PUT">PUT</span> <code>/api/user</code></p>
            <h4>Headers</h4>
            <table>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Required</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>Authorization</td>
                    <td>Bearer &lt;JWT_TOKEN&gt;</td>
                    <td class="required">Yes</td>
                    <td>Valid token</td>
                </tr>
                <tr>
                    <td>Content-Type</td>
                    <td>application/json</td>
                    <td class="required">Yes</td>
                    <td>JSON body</td>
                </tr>
            </table>
            <pre>{
  "name": "Updated Name",
  "email": "updated@example.com"
}</pre>
            <pre>{
  "status": true,
  "message": "User updated successfully"
}</pre>

            <!-- Logout -->
            <h3>6. Logout</h3>
            <p><span class="method POST">POST</span> <code>/api/logout</code></p>
            <h4>Headers</h4>
            <table>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Required</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>Authorization</td>
                    <td>Bearer &lt;JWT_TOKEN&gt;</td>
                    <td class="required">Yes</td>
                    <td>Valid token</td>
                </tr>
            </table>
            <pre>{
  "status": true,
  "message": "Successfully logged out"
}</pre>

        </section>

        <!-- ==================== CRUD ==================== -->
        <section>
            <h2>CRUD: Data Management APIs</h2>

            <!-- List All Data -->
            <h3>1. List All Data</h3>
            <p><span class="method GET">GET</span> <code>/api/list-data</code></p>
            <h4>Headers</h4>
            <table>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Required</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>Authorization</td>
                    <td>Bearer &lt;JWT_TOKEN&gt;</td>
                    <td class="required">Yes</td>
                    <td>Valid token</td>
                </tr>
            </table>
            <pre>{
  "status": true,
  "message": "Data retrieved successfully",
  "data": [
    {
      "id": 1,
      "full_name": "John Smith",
      "languages": "English,Hindi",
      "gender": "Male"
    }
  ]
}</pre>

            <!-- Create Data -->
            <h3>2. Create Data</h3>
            <p><span class="method POST">POST</span> <code>/api/submit-data</code></p>
            <h4>Headers</h4>
            <table>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Required</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>Authorization</td>
                    <td>Bearer &lt;JWT_TOKEN&gt;</td>
                    <td class="required">Yes</td>
                    <td>Valid token</td>
                </tr>
                <tr>
                    <td>Content-Type</td>
                    <td>multipart/form-data</td>
                    <td class="required">Yes</td>
                    <td>For file upload</td>
                </tr>
            </table>
            <h4>Request Payload</h4>
            <table>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Validation Rules</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>fullName</td>
                    <td>string</td>
                    <td class="required">Yes</td>
                    <td>min:3, max:255</td>
                    <td>Full name</td>
                </tr>
                <tr>
                    <td>languages</td>
                    <td>array</td>
                    <td class="required">Yes</td>
                    <td>in:English,Hindi,Bengali</td>
                    <td>Languages</td>
                </tr>
                <tr>
                    <td>gender</td>
                    <td>string</td>
                    <td class="required">Yes</td>
                    <td>Male, Female, Other</td>
                    <td>Gender</td>
                </tr>
                <tr>
                    <td>country</td>
                    <td>string</td>
                    <td class="required">Yes</td>
                    <td>-</td>
                    <td>Country</td>
                </tr>
                <tr>
                    <td>message</td>
                    <td>string</td>
                    <td class="optional">No</td>
                    <td>max:1000</td>
                    <td>Optional</td>
                </tr>
                <tr>
                    <td>dob</td>
                    <td>date</td>
                    <td class="required">Yes</td>
                    <td>before_or_equal:today</td>
                    <td>Date of birth</td>
                </tr>
                <tr>
                    <td>phone</td>
                    <td>string</td>
                    <td class="required">Yes</td>
                    <td>10 digits</td>
                    <td>Phone</td>
                </tr>
                <tr>
                    <td>photo</td>
                    <td>file</td>
                    <td class="required">Yes</td>
                    <td>jpeg/png/jpg/webp, max:2MB</td>
                    <td>Profile photo</td>
                </tr>
            </table>
            <pre>{
  "status": true,
  "message": "Data saved successfully!"
}</pre>

            <!-- Show Data -->
            <h3>3. Show Data by ID</h3>
            <p><span class="method GET">GET</span> <code>/api/show-data/{id}</code></p>
            <pre>{
  "status": true,
  "data": {
    "id": 1,
    "full_name": "John Smith"
  }
}</pre>

            <!-- Update Data -->
            <h3>4. Update Data</h3>
            <p><span class="method PUT">PUT</span> <code>/api/update-data/{id}</code></p>

            <h4>Headers</h4>
            <table>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Required</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>Authorization</td>
                    <td>Bearer &lt;JWT_TOKEN&gt;</td>
                    <td class="required">Yes</td>
                    <td>Valid JWT token</td>
                </tr>
                <tr>
                    <td>Content-Type</td>
                    <td>multipart/form-data</td>
                    <td class="required">Yes</td>
                    <td>Required for file uploads</td>
                </tr>
            </table>

            <h4>Request Payload</h4>
            <table>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Validation Rules</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>fullName</td>
                    <td>string</td>
                    <td class="optional">No</td>
                    <td>min:3, max:255</td>
                    <td>Full name</td>
                </tr>
                <tr>
                    <td>languages</td>
                    <td>array</td>
                    <td class="optional">No</td>
                    <td>in:English,Hindi,Bengali</td>
                    <td>Languages</td>
                </tr>
                <tr>
                    <td>gender</td>
                    <td>string</td>
                    <td class="optional">No</td>
                    <td>Male, Female, Other</td>
                    <td>Gender</td>
                </tr>
                <tr>
                    <td>country</td>
                    <td>string</td>
                    <td class="optional">No</td>
                    <td>-</td>
                    <td>Country</td>
                </tr>
                <tr>
                    <td>message</td>
                    <td>string</td>
                    <td class="optional">No</td>
                    <td>max:1000</td>
                    <td>Optional message</td>
                </tr>
                <tr>
                    <td>dob</td>
                    <td>date</td>
                    <td class="optional">No</td>
                    <td>before_or_equal:today</td>
                    <td>Date of birth</td>
                </tr>
                <tr>
                    <td>phone</td>
                    <td>string</td>
                    <td class="optional">No</td>
                    <td>10 digits</td>
                    <td>Phone number</td>
                </tr>
                <tr>
                    <td>photo</td>
                    <td>file</td>
                    <td class="optional">No</td>
                    <td>jpeg/png/jpg/webp, max:2MB</td>
                    <td>Profile photo</td>
                </tr>
            </table>

            <h4>Example Request</h4>
            <pre>{
  "fullName": "John Smith Updated",
  "languages": ["English"],
  "gender": "Male",
  "country": "India",
  "dob": "1990-05-15",
  "phone": "9876543210"
}</pre>

            <h4>✅ Success Response</h4>
            <pre>{
  "status": true,
  "message": "Data updated successfully!",
  "data": {
    "id": 1,
    "full_name": "John Smith Updated",
    "languages": "English",
    "gender": "Male",
    "country": "India",
    "dob": "1990-05-15",
    "phone": "9876543210",
    "photo": "new-photo.jpg"
  },
  "image_path": "https://your-domain.com/uploads/photos/new-photo.jpg"
}</pre>

            <h4>❌ Error Responses</h4>
            <pre>{
  "status": false,
  "message": "Data not found"
}</pre>
            <pre>{
  "status": false,
  "errors": {
    "phone": ["The phone must be 10 digits."]
  }
}</pre>

            <!-- Delete Data -->
            <h3>5. Delete Data</h3>
            <p><span class="method DELETE">DELETE</span> <code>/api/delete-data/{id}</code></p>

            <h4>Headers</h4>
            <table>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Required</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>Authorization</td>
                    <td>Bearer &lt;JWT_TOKEN&gt;</td>
                    <td class="required">Yes</td>
                    <td>Valid JWT token</td>
                </tr>
            </table>

            <h4>✅ Success Response</h4>
            <pre>{
  "status": true,
  "type": "success",
  "text": "Data deleted successfully."
}</pre>

            <h4>❌ Error Responses</h4>
            <pre>{
  "status": false,
  "message": "Data not found"
}</pre>
            <pre>{
  "status": false,
  "message": "Unauthorized"
}</pre>

        </section>

    </main>
</body>

</html>