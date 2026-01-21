# DeckMemo

DeckMemo is a web application designed to help users memorize the order of a shuffled deck of playing cards. It's a useful tool for memory athletes, magicians, or anyone interested in improving their memory skills.

The application allows users to create and manage multiple "memory decks". For each deck, the user can assign a unique identifier (a word, number, or image) to each of the 52 cards. This is a common technique used in memory palaces.

## Features

- **Create Custom Decks:** Users can create personalized decks and assign mnemonic identifiers to each card.
- **Memorization Mode:** A training mode that displays the cards in a sequence to be memorized.
- **Recall Mode:** A practice mode where users can test their memory by trying to recall the correct sequence of cards.
- **Deck Management:** Users can view, edit, and manage their created decks.

## How it Works

The core of DeckMemo is based on the idea of creating associations between a known sequence (the order of cards in a deck) and a set of personal cues (the identifiers). By practicing with the application, users can strengthen these mental connections and improve their ability to recall the entire deck.

## Technologies Used

- **PHP:** For the back-end logic and data management.
- **JavaScript:** For the interactive front-end, including the card display and navigation.
- **HTML/CSS:** For the structure and styling of the application.
- **Docker:** The project is containerized for easy setup and deployment.

## How to Use

1. **Clone the repository:**
   ```bash
   git clone https://github.com/flps/deckmemo.git
   ```
2. **Navigate to the project directory:**
   ```bash
   cd deckmemo
   ```
3. **Start the application using Docker Compose:**
   ```bash
   docker-compose up -d
   ```
4. **Access the application in your browser:**
   [http://localhost:8080](http://localhost:8080)
