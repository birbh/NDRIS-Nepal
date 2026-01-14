#!/bin/bash

# NDRIS-Nepal Quick Deploy Script
# Pushes to GitHub and provides deployment instructions

echo "🚀 NDRIS-Nepal Auto-Deployment Setup"
echo "======================================"
echo ""

# Navigate to project root
cd "$(dirname "$0")/.."

# Check if git is initialized
if [ ! -d .git ]; then
    echo "📦 Initializing Git repository..."
    git init
    git branch -M main
fi

# Add all files
echo "📝 Adding files to Git..."
git add .

# Commit
echo "💾 Creating commit..."
read -p "Enter commit message (or press Enter for default): " commit_msg
if [ -z "$commit_msg" ]; then
    commit_msg="Update NDRIS-Nepal project"
fi
git commit -m "$commit_msg"

# Check if remote exists
if ! git remote | grep -q origin; then
    echo ""
    echo "🔗 Setting up GitHub remote..."
    read -p "Enter your GitHub repository URL: " repo_url
    git remote add origin "$repo_url"
fi

# Push to GitHub
echo ""
echo "⬆️  Pushing to GitHub..."
git push -u origin main

echo ""
echo "✅ Code pushed to GitHub successfully!"
echo ""
echo "📋 Next Steps - Choose a deployment platform:"
echo ""
echo "1️⃣  REPLIT (Easiest - Recommended for students)"
echo "   → Go to: https://replit.com"
echo "   → Click 'Import from GitHub'"
echo "   → Select your repository"
echo "   → Click Run"
echo ""
echo "2️⃣  RAILWAY (Good free tier)"
echo "   → Go to: https://railway.app"
echo "   → 'New Project' → 'Deploy from GitHub'"
echo "   → Add MySQL database"
echo ""
echo "3️⃣  RENDER (Auto-deployment)"
echo "   → Go to: https://render.com"
echo "   → 'New +' → 'Web Service'"
echo "   → Connect GitHub repo"
echo ""
echo "📖 Full instructions: See DEPLOYMENT.md"
echo ""
echo "🎉 Your repository is now ready for auto-deployment!"
echo "   Every 'git push' will update your live site automatically."
