<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            // Fiksi (category_id: 1)
            ['category_id' => 1, 'title' => 'Laskar Pelangi', 'author' => 'Andrea Hirata', 'publisher' => 'Bentang Pustaka', 'year' => 2005, 'stock' => 5],
            ['category_id' => 1, 'title' => 'Bumi Manusia', 'author' => 'Pramoedya Ananta Toer', 'publisher' => 'Lentera Dipantara', 'year' => 1980, 'stock' => 3],
            ['category_id' => 1, 'title' => 'Negeri 5 Menara', 'author' => 'Ahmad Fuadi', 'publisher' => 'Gramedia', 'year' => 2009, 'stock' => 4],
            ['category_id' => 1, 'title' => 'Dilan 1990', 'author' => 'Pidi Baiq', 'publisher' => 'Pastel Books', 'year' => 2014, 'stock' => 6],
            // Sains (category_id: 2)
            ['category_id' => 2, 'title' => 'A Brief History of Time', 'author' => 'Stephen Hawking', 'publisher' => 'Bantam Books', 'year' => 1988, 'stock' => 3],
            ['category_id' => 2, 'title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'publisher' => 'Harper', 'year' => 2011, 'stock' => 4],
            ['category_id' => 2, 'title' => 'The Selfish Gene', 'author' => 'Richard Dawkins', 'publisher' => 'Oxford University Press', 'year' => 1976, 'stock' => 2],
            // Teknologi (category_id: 3)
            ['category_id' => 3, 'title' => 'Clean Code', 'author' => 'Robert C. Martin', 'publisher' => 'Prentice Hall', 'year' => 2008, 'stock' => 5],
            ['category_id' => 3, 'title' => 'The Pragmatic Programmer', 'author' => 'Andrew Hunt', 'publisher' => 'Addison-Wesley', 'year' => 1999, 'stock' => 3],
            ['category_id' => 3, 'title' => 'Design Patterns', 'author' => 'Gang of Four', 'publisher' => 'Addison-Wesley', 'year' => 1994, 'stock' => 2],
            // Sejarah (category_id: 4)
            ['category_id' => 4, 'title' => 'Sejarah Indonesia Modern', 'author' => 'M.C. Ricklefs', 'publisher' => 'Gadjah Mada University Press', 'year' => 2005, 'stock' => 4],
            ['category_id' => 4, 'title' => 'Perang Dunia II', 'author' => 'Antony Beevor', 'publisher' => 'Weidenfeld & Nicolson', 'year' => 2012, 'stock' => 3],
            // Biografi (category_id: 5)
            ['category_id' => 5, 'title' => 'Steve Jobs', 'author' => 'Walter Isaacson', 'publisher' => 'Simon & Schuster', 'year' => 2011, 'stock' => 4],
            ['category_id' => 5, 'title' => 'Elon Musk', 'author' => 'Ashlee Vance', 'publisher' => 'Ecco', 'year' => 2015, 'stock' => 3],
            ['category_id' => 5, 'title' => 'Soekarno: An Autobiography', 'author' => 'Cindy Adams', 'publisher' => 'Gunung Agung', 'year' => 1965, 'stock' => 2],
            // Filsafat (category_id: 6)
            ['category_id' => 6, 'title' => 'Dunia Sophie', 'author' => 'Jostein Gaarder', 'publisher' => 'Mizan', 'year' => 1991, 'stock' => 5],
            ['category_id' => 6, 'title' => 'Meditations', 'author' => 'Marcus Aurelius', 'publisher' => 'Penguin Classics', 'year' => 180, 'stock' => 3],
            // Ekonomi (category_id: 7)
            ['category_id' => 7, 'title' => 'Rich Dad Poor Dad', 'author' => 'Robert Kiyosaki', 'publisher' => 'Warner Books', 'year' => 1997, 'stock' => 6],
            ['category_id' => 7, 'title' => 'The Wealth of Nations', 'author' => 'Adam Smith', 'publisher' => 'W. Strahan', 'year' => 1776, 'stock' => 2],
            ['category_id' => 7, 'title' => 'Freakonomics', 'author' => 'Steven Levitt', 'publisher' => 'William Morrow', 'year' => 2005, 'stock' => 4],
            // Pendidikan (category_id: 8)
            ['category_id' => 8, 'title' => 'Mindset', 'author' => 'Carol Dweck', 'publisher' => 'Random House', 'year' => 2006, 'stock' => 5],
            ['category_id' => 8, 'title' => 'Make It Stick', 'author' => 'Peter Brown', 'publisher' => 'Harvard University Press', 'year' => 2014, 'stock' => 3],
        ];

        foreach ($books as $book) {
            Book::create(array_merge($book, [
                'description' => 'Deskripsi buku '.$book['title'].'. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'location_rack' => 'Rak '.chr(rand(65, 70)).'-0'.rand(1, 9),
                'status' => 'available',
            ]));
        }
    }
}