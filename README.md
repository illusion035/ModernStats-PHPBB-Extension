<h1 align="center">📊 Modern Statistics Extension for phpBB</h1>

## 🧩 Version: 1.1.0  
**👤 Author:** Illusion  
**🧷 Compatibility:** phpBB 3.2.0+

---

## 📌 Description  
Modern Statistics is a sleek and informative extension for phpBB that adds attractive and modern statistics to your forum.  
It displays key forum data in a visually appealing way using a modern Bootstrap-based design and icons.

---

## ⚙️ Features

### 1. 📈 General Statistics:
- 👥 Total Members  
- 🧵 Total Topics  
- 💬 Total Posts  
- 🏆 Most Active User  
- 👁️ Total Views  
- 🆕 Newest Member  
- 📅 Posts Per Day  
- 📊 Active Users % (last 24h)

### 2. 🕒 Latest Posts:
- 🗨️ Displays recent forum posts  
- 👤 Includes user avatars  
- 🏷️ Shows topic title, forum name, and post time  
- 🔢 Configurable number of posts shown  
- 🔒 Shows a lock icon for locked topics

### 3. 🧑💻 Latest Registered Users:
- 👥 Shows newly registered users  
- 🖼️ Includes avatars  
- 📆 Shows registration date and post count  
- 🔧 Configurable number of users shown

### 4. 🎨 Theme Support:
- 🌙 Dark Theme  
- ☀️ Light Theme  
- 🔧 Configurable in ACP

### 5. 🚫 Group Exclusion for Most Active User:
- 🛡️ Exclude specific groups from "Most Active User" statistic  
- 👮 Perfect for excluding Administrators, Moderators, or Bots  
- ✅ Multi-select interface in ACP

---

## 🆕 What's New in v1.1.0

### ✨ New Features:
- **🚫 Group Exclusion**: Exclude specific user groups from "Most Active User" (Top Poster) statistic
- **🎨 Theme Selection**: Choose between Light and Dark themes from ACP

### ⚡ Performance Improvements:
- **🚀 Optimized Database Queries**: Reduced 5 database queries by using phpBB's built-in config values
- Uses `num_users`, `num_topics`, `num_posts`, `newest_username`, `board_startdate` from phpBB config
- **~50-75ms faster page load** according to user reports

### 🐛 Bug Fixes:
- Fixed: Blue bar remaining when all features are disabled (`S_MODERNSTATS_ENABLED` now properly checks if any feature is enabled)
- Fixed: PHP warnings for "Trying to access array offset on bool" when no top poster exists
- Fixed: Avatar display using phpBB's native `phpbb_get_avatar()` function with proper key mapping
- Fixed: Undefined constant errors by using global namespace prefix for phpBB constants

### 📝 Code Quality (phpBB Validation):
- Removed unused `listener.php` file
- Removed custom phpBB constant redefinitions
- Moved custom avatar function to class method
- Fixed HTML formatting in templates
- Using `{L_COLON}` instead of hardcoded colons
- Following phpBB coding guidelines (tabs, bracket placement)

---

## 📥 Installation

1. Download and extract the files  
2. Upload the `illusion` folder to your forum's `ext/` directory  
3. Go to **ACP → Customise → Manage extensions**  
4. Find **Modern Statistics** and click **Enable**

---

## 🔄 Upgrade from v1.0.0

1. **Disable** the extension in ACP → Customise → Manage extensions
2. Replace the files in `ext/illusion/modernstats/` with the new version
3. **Enable** the extension again (new migration will run automatically)
4. Configure the new settings in ACP → Extensions → Modern Statistics

---

## 🔧 Configuration

In **ACP → Extensions → Modern Statistics → Settings**, you can configure:

- 🎨 **Theme**: Choose between Light and Dark themes
- 🧭 **Display location** of the statistics block  
- ✅ **Enable/disable** general statistics section
- 🚫 **Exclude groups** from Most Active User statistic (NEW!)
- 🗨️ **Enable/disable** latest posts  
- 🔢 **Number of latest posts** to show (1-50)  
- 👤 **Enable/disable** latest users  
- 🔢 **Number of latest users** to show (1-50)

---

## 🌍 Supported Languages
- 🇬🇧 English (en)  
- 🇧🇬 Bulgarian (bg)

---

## 📎 Requirements

- 🧩 phpBB 3.2.0 or later  
- 🐘 PHP 7.1 or later

---

## 🛠️ Support  
Discord **illusion034** for **bugs / issues / suggestions**.

---

## 📜 Changelog

### v1.1.0 (2026-01-20)
- ✨ Added: Group exclusion for Most Active User
- ✨ Added: Light/Dark theme selection
- ⚡ Improved: Performance optimization (5 fewer DB queries)
- 🐛 Fixed: S_MODERNSTATS_ENABLED logic
- 🐛 Fixed: Avatar display issues
- 🐛 Fixed: PHP warnings when no top poster exists
- 📝 Improved: Code quality for phpBB validation

### v1.0.0 (Initial Release)
- 📊 General statistics display
- 🕒 Latest posts section
- 🧑💻 Latest users section
- 🌍 English and Bulgarian translations

---

## 🖼️ Screenshots  
<img width="1826" height="864" alt="Dark" src="https://github.com/user-attachments/assets/d04d0606-0f1f-4a30-b1d7-95b6ccaa34a9" />
<img width="1827" height="860" alt="Light" src="https://github.com/user-attachments/assets/0156ea77-3e76-4b73-bfba-1780b1a0489f" />
<img width="1661" height="534" alt="Screenshot_1" src="https://github.com/user-attachments/assets/7371a1a5-0032-43be-9307-a5a0317fe84b" />

