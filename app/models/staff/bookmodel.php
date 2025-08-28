    <?php
    require_once config::getdbPath();

    /**
 * BookModel
 * Handles all operations related to books, categories, and languages.
 */

    class BookModel
    {     /**
     * Get all books with pagination.
     */

        public static function getAllBooks($page, $resultsPerPage, $status = 'Active')
        {
            $statusId = ($status === 'Active') ? 1 : 2;

            $pageResults = ($page - 1) * $resultsPerPage;
            $totalBooks = self::getTotalBooks($statusId);

            $query = "SELECT * FROM book 
            INNER JOIN category ON book.category_id = category.category_id 
            INNER JOIN `status` ON book.status_id = status.status_id 
            INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
            WHERE `book`.`status_id`=?
            LIMIT ? OFFSET ?";
            $params = [$statusId, $resultsPerPage, $pageResults];
            $types = "iii";
            $rs = Database::search($query, $params, $types);

            $books = [];

            while ($row = $rs->fetch_assoc()) {
                $books[] = $row;
            }
            return [
                'total' => $totalBooks,
                'results' => $books
            ];
        }

        private static function getTotalBooks($statusId)
        {
            $query = "SELECT COUNT(*) AS total FROM book 
            INNER JOIN category ON book.category_id = category.category_id 
            INNER JOIN `status` ON book.status_id = status.status_id 
            INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
            WHERE `book`.`status_id`=?";
            $params = [$statusId];
            $types = "i";
            $result = Database::search($query, $params, $types);

            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        }
        

        public static function searchBooks($bookid, $title, $isbn, $category_id, $language_id, $status = 'Active', $page, $resultsPerPage)
        {
            $statusId = ($status === 'Active') ? 1 : 2;
            $pageResults = ($page - 1) * $resultsPerPage;

            // get total results securely
            $totalSearch = self::getTotalSearchResults($title, $isbn, $bookid, $statusId);

            $query = "SELECT * FROM `book`
                INNER JOIN category ON book.category_id = category.category_id 
                INNER JOIN `status` ON book.status_id = status.status_id 
                INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
                WHERE `book`.`status_id` = ?";

            $params = [$statusId];
            $types = "i";

            // Optional filters
            if (!empty($category_id)) {
                $query .= " AND `book`.`category_id` = ?";
                $params[] = $category_id;
                $types .= "i";
            }
            if (!empty($language_id)) {
                $query .= " AND `book`.`language_id` = ?";
                $params[] = $language_id;
                $types .= "i";
            }
            if (!empty($bookid)) {
                $query .= " AND `book_id` LIKE ?";
                $params[] = "%$bookid%";
                $types .= "s";
            }
            if (!empty($title)) {
                $query .= " AND `title` LIKE ?";
                $params[] = "%$title%";
                $types .= "s";
            }
            if (!empty($isbn)) {
                $query .= " AND `isbn` LIKE ?";
                $params[] = "%$isbn%";
                $types .= "s";
            }

            // Pagination
            $query .= " LIMIT ? OFFSET ?";
            $params[] = $resultsPerPage;
            $params[] = $pageResults;
            $types .= "ii";

            // Execute with prepared statement
            $rs = Database::search($query, $params, $types);

            $books = [];
            while ($row = $rs->fetch_assoc()) {
                $books[] = $row;
            }

            return ['results' => $books, 'total' => $totalSearch];
        }


        private static function getTotalSearchResults($title, $isbn, $bookid, $statusId, $category_id = null, $language_id = null)
        {
            $countQuery = "SELECT COUNT(*) as total FROM `book`
                    INNER JOIN category ON book.category_id = category.category_id 
                    INNER JOIN `status` ON book.status_id = status.status_id 
                    INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` 
                    WHERE `book`.`status_id` = ?";

            $params = [$statusId];
            $types  = "i";

            if (!empty($category_id)) {
                $countQuery .= " AND `book`.`category_id` = ?";
                $params[] = $category_id;
                $types .= "i";
            }
            if (!empty($language_id)) {
                $countQuery .= " AND `book`.`language_id` = ?";
                $params[] = $language_id;
                $types .= "i";
            }
            if (!empty($bookid)) {
                $countQuery .= " AND `book_id` LIKE ?";
                $params[] = "%$bookid%";
                $types .= "s";
            }
            if (!empty($title)) {
                $countQuery .= " AND `title` LIKE ?";
                $params[] = "%$title%";
                $types .= "s";
            }
            if (!empty($isbn)) {
                $countQuery .= " AND `isbn` LIKE ?";
                $params[] = "%$isbn%";
                $types .= "s";
            }

            $result = Database::search($countQuery, $params, $types);
            $row = $result->fetch_assoc();

            return $row['total'] ?? 0;
        }

        public static function loadBookDetails($id)
        {
            $query = "SELECT * FROM `book` INNER JOIN `category` ON `book`.`category_id` = `category`.`category_id` INNER JOIN `status` ON `book`.`status_id` = `status`.`status_id`INNER JOIN `language` ON `book`.`language_id` = `language`.`language_id` WHERE `book_id` = ?";
            $params = [$id];
            $types = "s";
            $rs = Database::search($query, $params, $types);

            return $rs;
        }

        public static function getAllCategories()
        {
            // Modified query to also get the count of books for each category
            $query = "SELECT category.category_id, category.category_name, COUNT(book.book_id) AS book_count
                    FROM category
                    LEFT JOIN book ON category.category_id = book.category_id
                    GROUP BY category.category_id, category.category_name";

            // Execute the query
            $result = Database::search($query);

            // Initialize an array to store categories with their book count
            $categories = [];

            // If there are results, loop through and fetch each category with book count
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $categories[] = [
                        'category_id' => $row['category_id'],
                        'category_name' => $row['category_name'],
                        'book_count' => $row['book_count'] // Count of books in each category
                    ];
                }
            }

            // Return the categories with book counts
            return $categories;
        }


        public static function getLanguages()
        {
            $query = "SELECT `language_id`, `language_name` FROM `language`";
            $result = Database::search($query);

            $languages = [];
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $languages[] = $row;
                }
            }

            return $languages;
        }

        public static function updateBookDetails($book_id, $isbn, $title, $author, $category, $language, $pubYear, $quantity, $description)
        {
            // Get the old qty and available_qty from the database

            $query = "SELECT `qty`, `available_qty` FROM `book` WHERE `book_id` = ?";
            $params = [$book_id];
            $types = "s";
            $rs = Database::search($query, $params, $types);

            $row = $rs->fetch_assoc();
            $old_qty = $row['qty'];
            $old_available_qty = $row['available_qty'];  // Corrected here to use available_qty

            // Calculate the difference in quantity
            $qty_difference = $quantity - $old_qty;

            // Update available_qty based on the difference
            if ($qty_difference > 0) {
                $new_available_qty = $old_available_qty + $qty_difference;
            } else {
                $new_available_qty = $old_available_qty - abs($qty_difference);
            }

            // Update the book details in the database
            $query = "UPDATE book SET
                `isbn` = ?,
                `title` = ?, 
                `author` = ?, 
                `pub_year` = ?, 
                `qty` = ?, 
                `available_qty` = ?,
                `category_id` = ?, 
                `language_id` = ?, 
                `description` = ?
                WHERE `book_id` = ?";
            $params = [$isbn, $title, $author, $pubYear, $quantity, $new_available_qty, $category, $language, $description, $book_id];
            $types = "sssiiiiiss";
            Database::ud($query, $params, $types);

            return true;
        }

        public static function isbnExists($isbn)
        {

            $query = "SELECT * FROM `book` WHERE `isbn` = ?";
            $params = [$isbn];
            $types = "s";
            $result = Database::search($query, $params, $types);

            return $result !== null;
        }


        public static function addBook($isbn, $author, $title, $category, $language, $pub, $qty, $des, $coverpage)
        {
            // Generate a unique book ID
            $book_id = self::generateID();

            // Insert the new book into the database
            $query = "INSERT INTO `book`(`book_id`,`isbn`,`title`,`author`,`pub_year`,`description`,`cover_page`,`qty`,
            `available_qty`,`category_id`,`language_id`,`status_id`) 
            VALUES (?,?, ?, ?, ?, ?,?, ?, ?, ?,?,'1')";
            $params = [$book_id, $isbn, $title, $author, $pub, $des, $coverpage, $qty, $qty, $category, $language];
            $types = "ssssissiiii";
            Database::insert($query, $params, $types);

            return true;
        }


        public static function generateID()
        {
            // Query to get the latest staff_id
            $query = "SELECT book_id FROM `book` ORDER BY book_id DESC LIMIT 1";
            $result = Database::search($query);

            if ($result->num_rows > 0) {
                // Fetch the last book ID
                $row = $result->fetch_assoc();
                $lastBookID = $row['book_id'];

                // Extract numeric part and increment
                $number = (int)substr($lastBookID, 2);
                $newNumber = $number + 1;

                // Format new ID with zero-padding
                $newBookID = "B-" . str_pad($newNumber, 6, "0", STR_PAD_LEFT);
            } else {
                // If no book exists, start from B-000001
                $newBookID = "B-000001";
            }
            return $newBookID;
        }

        public static function addCategory($category)
        {
            $query = "INSERT INTO `category`(`category_name`) VALUES (?)";
            $params = [$category];
            $types = "s";
            Database::insert($query, $params, $types);

            return true;
        }

        public static function deactivateBook($book_id)
        {
            $query = "UPDATE `book` SET `status_id`='2' WHERE `book_id`=?";
            $params = [$book_id];
            $types = "s";
            Database::ud($query, $params, $types);

            return true;
        }

        public static function activateBook($book_id)
        {
            $query = "UPDATE `book` SET `status_id`='1' WHERE `book_id`=?";
            $params = [$book_id];
            $types = "s";
            Database::ud($query, $params, $types);

            return true;
        }

        public static function deleteCategory($category_id)
        {
            $query = "DELETE FROM `category` WHERE `category_id` = ?";
            $params = [$category_id];
            $types = "i";
            Database::ud($query, $params, $types);

            return true;
        }
    }
