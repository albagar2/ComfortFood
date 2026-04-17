@echo off
cd "C:\Users\bacia\Desktop\portfolio\proyectos\ProyectoFinal2DAW - copia\ComfortFood"
git init
git add .
git commit -m "Initial commit for ComfortFood project from Portfolio"
git branch -M main
git remote add origin https://github.com/albagar2/ComfortFood.git
git push -u origin main
echo DONE
