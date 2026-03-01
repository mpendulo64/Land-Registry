# Land-Registry
🏡 LandRegistry DApp
Blockchain-Based Land Ownership & Transfer System (Cardano – Plutus V2)
Secure, immutable land registration and ownership transfer powered by Cardano smart contracts, NFT-based land titles, and a PHP + MySQL backend.

📚 Table of Contents
Overview
Key Features
System Architecture
Smart Contracts
Frontend
Backend
Database Schema
Wallet & Blockchain Flow
User Roles
How the App Works (Step-by-Step)
Installation & Setup
Environment Configuration
Security Design
Transaction Lifecycle
Architecture Diagrams
Future Improvements
🌍 Overview
LandRegistry DApp is a decentralized land registration system that:

Mints each land parcel as a unique NFT
Locks ownership inside a Plutus V2 validator
Requires authority + owner signatures for transfers
Stores metadata and history in a backend database
Allows wallet-based verification and tracking
Each land parcel becomes:

1 Parcel = 1 NFT = 1 Immutable Ownership Record
🚀 Key Features
🔐 Immutable Land Titles
Each parcel is minted as a unique NFT tied to a policy ID.

👮 Authority-Controlled Registration
Only the authority wallet can approve land registrations.

🔄 Secure Ownership Transfer
Transfers require:

Current owner signature
Authority signature
NFT remains locked at validator
Owner field updated in datum
📜 Full Transaction History
Users can search any wallet address and see:

Registrations
Transfers
Status
Transaction hashes
🖼 Land Document Storage
Documents stored on server (linked to parcel)

🌐 Wallet Integration
Built with:

Lucid
CIP-30 compatible wallets
Blockfrost for chain indexing
🏗 System Architecture
This project uses a hybrid architecture:

On-chain: Plutus V2 Smart Contracts
Off-chain: Lucid (JavaScript)
Frontend: PHP + HTML + CSS
Backend: PHP + MySQL
Blockchain: Cardano Preprod
⛓ Smart Contracts
1️⃣ Land Minting Policy
Mints NFT per parcel
TokenName = SHA256(parcelId)
Authority-signed minting
2️⃣ Land Registry Validator
Datum:

data LandDatum = LandDatum
  { ldParcelId :: BuiltinByteString
  , ldOwnerPkh :: PubKeyHash
  }
Redeemer:

data LandRedeemer
  = Transfer PubKeyHash
Validation rules:

Authority must sign
Current owner must sign
NFT must remain locked
Parcel ID must not change
Owner must update correctly
💻 Frontend
Built with:

PHP
HTML5
CSS3
Lucid (JS module)
Responsive dashboard UI
Main Pages:

Page	Description
index.php	Landing page
login.php	Authentication
app.php	Dashboard
authority.php	Register land
owner.php	Transfer ownership
assets.php	View owned lands
history.php	Transaction history
🗄 Backend
Backend stack:

PHP (PDO)
MySQL
CSRF protection
Authentication middleware
JSON APIs
APIs include:

history_api.php
register_land.php
transfer_land.php
🧮 Database Schema
lands table
Column	Type	Description
id	INT	Primary key
parcel_id	VARCHAR	Unique parcel
policy_id	VARCHAR	NFT policy
token_name	VARCHAR	Hashed name
initial_owner_pkh	VARCHAR	First owner
current_owner_pkh	VARCHAR	Current owner
created_at	DATETIME	Timestamp
tx_hash	VARCHAR	Registration tx
land_transfers table
Column	Type
id	INT
parcel_id	VARCHAR
from_pkh	VARCHAR
to_pkh	VARCHAR
status	VARCHAR
tx_hash	VARCHAR
created_at	DATETIME
👥 User Roles
🏢 Authority
Registers land
Signs transfers
👤 Land Owner
Holds NFT
Signs transfer requests
🔎 Public User
View history
Verify ownership
🧭 How the App Works (Step-by-Step)
🏡 1. Register Land
Authority connects wallet
Upload land document
Enter parcel ID
Mint NFT
NFT locked at validator
Land stored in DB
🔄 2. Transfer Ownership
Current owner initiates transfer

Both owner & authority sign

Validator checks:

Authority signed
Owner signed
NFT preserved
Owner updated
Transfer recorded in DB

📜 3. View History
User enters wallet address

Address converted to PKH

Backend fetches:

Lands
Transfers
Displays:

Status
Tx Hash
Parcel ID
⚙ Installation & Setup
Requirements
PHP 8+
MySQL
Node.js (for Lucid)
Cabal / GHC (for Plutus)
Blockfrost API key
1️⃣ Clone Project
git clone <repo>
2️⃣ Configure Database
Edit:

config.php
Add:

DB_HOST
DB_NAME
DB_USER
DB_PASS
3️⃣ Compile Smart Contract
cabal build
cabal run
Outputs:

land_validator.plutus
land_validator.cbor
4️⃣ Configure Lucid
Edit:

BLOCKFROST_KEY
NETWORK
POLICY_ID
VALIDATOR_ADDRESS
5️⃣ Deploy to Hosting
Upload to:

InfinityFree / VPS
Or Localhost (XAMPP)
🔐 Security Design
✔ CSRF protection ✔ Signature verification ✔ NFT locked in validator ✔ Immutable parcelId ✔ Dual-signature transfer ✔ Authority enforcement

🔄 Transaction Lifecycle
Mint → Lock → Transfer → Update Datum → Record DB → View History
🧱 Architecture Diagrams
🔷 Overall Architecture
Unable to render rich display

flowchart LR
    User -->|Connect Wallet| Frontend
    Frontend -->|Lucid Tx| Cardano
    Cardano -->|Validator| SmartContract
    Frontend --> Backend
    Backend --> Database
    Cardano --> Blockfrost
    Blockfrost --> Frontend
🔷 Registration Flow
Unable to render rich display

svg element not in render tree

For more information, see https://docs.github.com/get-started/writing-on-github/working-with-advanced-formatting/creating-diagrams#creating-mermaid-diagrams

sequenceDiagram
    participant A as Authority
    participant F as Frontend
    participant C as Cardano
    participant V as Validator
    participant DB as Database

    A->>F: Submit parcel
    F->>C: Mint NFT
    C->>V: Lock NFT
    F->>DB: Save land record
🔷 Transfer Flow
Unable to render rich display

svg element not in render tree

For more information, see https://docs.github.com/get-started/writing-on-github/working-with-advanced-formatting/creating-diagrams#creating-mermaid-diagrams

sequenceDiagram
    participant O as Owner
    participant A as Authority
    participant F as Frontend
    participant C as Cardano
    participant V as Validator

    O->>F: Request transfer
    A->>F: Approve
    F->>C: Submit Tx
    C->>V: Validate signatures
    V-->>C: Success
🔷 Smart Contract Logic
Unable to render rich display

flowchart TD
    Start --> CheckAuthority
    CheckAuthority --> CheckOwner
    CheckOwner --> CheckNFTInput
    CheckNFTInput --> CheckNFTOutput
    CheckNFTOutput --> CheckParcel
    CheckParcel --> UpdateOwner
    UpdateOwner --> Success
🔮 Future Improvements
IPFS document storage
Multi-signature authority
On-chain metadata registry
Fractional ownership
GIS map integration
Mobile wallet support
HTTPS SSL production deployment
📌 Important Notes
Always use HTTPS in production.
Never expose Blockfrost keys publicly.
Always validate PKH length (56 hex).
Always store tx_hash on registration.
🏁 Conclusion
LandRegistry DApp demonstrates:

Real-world NFT use case
Secure dual-signature validator
Hybrid on-chain/off-chain architecture
Production-style dashboard
End-to-end Cardano integration
