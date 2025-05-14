
<?php

require_once 'db.php'; 
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login_form.html');
    exit;
}

//Single responsibility Class(*) 
//Model 
class Book {
  public $id, $title, $author; 
  public function __construct($title, $author, $id = null){
    //this pointer 
    $this->title = $title; 
    $this->author = $author; 
    $this-> id = $id; 
  }
}

//Interface 
interface BookRepositoryInterface {

  //interface methods doesnot have body which helps in  abstraction 
  public function getAll();
  public function getById($id); 
  public function create(Book $book); 
  public function update(Book $book);
  public function delete($id); 
}


//Repository 
class BookRepository implements BookRepositoryInterface{
  private $conn; 
  public function __construct($pdo)
  {
    $this->conn = $pdo; 
  }

  public function getAll(){
    $stmt = $this->conn->query("SELECT * FROM books"); 
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getById($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM books WHERE id =?");
    $stmt->exec([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC); 
  }

  public function create(Book $book)
  {
    $stmt = $this->conn->prepare("INSERT INTO  books (title, author) VALUES (?,?)");
    return $stmt->exec([$book->title, $book->author]);  
  }

  public function update(Book $book)
  {
    $stmt = $this->conn->prepare("UPDATE books SET title =?, author =? WHERE id = ? "); 
    return $stmt->exec([$book->title, $book->author, $book-> id]); 

  }

  public function delete($id)
  {
    $stmt = $this->conn->prepare("DELECT FROM books WHERE id = ?"); 
    return $stmt->exec([$id]);
  }
}

//Service layer 
class BookService{
  private $repository; 
  public function __construct(BookRepositoryInterface $repo){
    $this->repository = $repo; 
  }

  public function listBooks(): mixed
  {
    return $this->repository->getAll();
  }

  public function addBook($title, $author)
  {
    $book = new Book($title, $author);
    return $this->repository->create($book);
  }

  public function  updateBook($id, $title, $author)
  {
    $book = new Book($title, $author, $id); 
    return $this->repository->update($book); 
  }

  public function deleteBook($id)
  {
    return $this->repository->delete($id);
  }

  public function getBook($id)
  {
    return $this->repository->getById($id); 
  }

}

//initialize services 
$repo = new BookRepository($pdo); 
$service = new BookService($repo); 

//Handle CRUD [Form]

if($_SERVER["REQUEST_METHOD"] === "POST"){
  if(isset($_POST['create'])){
    $service->addBook($_POST['title'], $_POST['author']); 
  }elseif(isset($_POST['update'])){
    $service->updateBook($_POST['id'], $_POST['title'], $_POST['author']); 
  }elseif($_POST['delete'])
  {
    $service->deleteBook(id: $_GET['delete']);
  }


  $editBook = null; 
  if(isset($_GET['edit']))
  {
    $editBook = $service-> getBook($_GET['edit']);

  }

  $books = $service->listBooks(); 
}
?>

<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
  <p>This is a protected page.</p>
  <a href="logout.php">Logout</a>
</body>
</html>

