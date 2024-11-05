<?php
require_once 'models.php';
require_once 'dbConfig.php';

if (isset($_POST['registerUserBtn'])) {

	$username = stripslashes(trim($_POST['username']));
	$password = stripslashes(trim($_POST['password']));
	$library_staff_id = $_POST['library_staff_id'];
	$employment_type = $_POST['employment_type'];
	$contact_number = $_POST['contact_number'];

	$plain_password = $password;

	$hashed_password = password_hash($password, PASSWORD_BCRYPT);

	if (!empty($username) && !empty($password) && !empty($library_staff_id) && !empty($employment_type) && !empty($contact_number)) {

		$insertQuery = insertNewUser($pdo, $username, $plain_password, $library_staff_id, $employment_type, $contact_number);

		if ($insertQuery) {
			header("Location: ../login.php");
			return true;
		} else {
			header("Location: ../register.php");
			return false;
		}
	} else {
		$_SESSION['message'] = "Please make sure the input fields 
		are not empty for registration!";

		header("Location: ../register.php");
	}

}

if (isset($_POST['loginUserBtn'])) {

	$username = stripslashes(trim($_POST['username']));
	$entered_password = stripslashes(trim($_POST['password']));

	if (!empty($username) && !empty($entered_password)) {

		$loginQuery = loginUser($pdo, $username, $entered_password);

		if ($loginQuery) {
			header("Location: ../index.php");
			return true;
		} else {
			header("Location: ../login.php");
			return false;
		}

	} else {
		$_SESSION['message'] = "Please make sure the input fields 
		are not empty for the login!";
		header("Location: ../login.php");
		return false;
	}

}

if (isset($_GET['logoutAUser'])) {
	unset($_SESSION['username']);
	header('Location: ../login.php');
}

if (isset($_POST['insertStudent'])) {

	session_start();

	$createdbyUser = $_SESSION['username'];

	$query = insertStudent($pdo, $_POST['fullname'], $_POST['section'], $createdbyUser);

	if ($query) {
		header("Location: ../index.php");
		exit();
	} else {
		echo "Insertion failed";
	}

}

if (isset($_POST['editStudentBtn'])) {

	session_start();

	$updatedbyUser = $_SESSION['username'];

	$query = updateStudent(
		$pdo,
		$_POST['fullname'],
		$_POST['section'],
		$_POST['registration_date'],
		$updatedbyUser,
		$_GET['student_id']
	);

	if ($query) {
		header("Location: ../index.php");
	} else {
		echo "Edit failed";
		;
	}

}

if (isset($_POST['deleteStudentBtn'])) {
	$query = deleteStudent($pdo, $_GET['student_id']);

	if ($query) {
		header("Location: ../index.php");
	} else {
		echo "Deletion failed";
	}
}

if (isset($_POST['insertNewBookBtn'])) {
	$query = insertBook($pdo, $_POST['title'], $_POST['author'], $_POST['genre'], $_POST['publication_year'], $_GET['student_id']);

	if ($query) {
		header("Location: ../viewRecords.php?student_id=" . $_GET['student_id']);
	} else {
		echo "Insertion failed";
	}
}

if (isset($_POST['editBookBtn'])) {

	session_start();

	$updatedbyUser = $_SESSION['username'];

	$query = updateBook($pdo, $_POST['title'], $_POST['author'], $_POST['genre'], $_POST['publication_year'], $updatedbyUser, $last_updated, $_GET['book_id']);

	if ($query) {
		header("Location: ../viewRecords.php?student_id=" . $_GET['student_id']);
		exit();
	} else {
		echo "Update failed";
	}

}

if (isset($_POST['deleteBookBtn'])) {
	$query = deleteBook($pdo, $_GET['book_id']);

	if ($query) {
		header("Location: ../viewRecords.php?student_id=" . $_GET['student_id']);
	} else {
		echo "Deletion failed";
	}
}
?>
