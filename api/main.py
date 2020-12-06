from fastapi import FastAPI

import crud

app = FastAPI()


@app.get("/nearDuplicate/")
def nearDuplicate(foldername: str):
   return crud.get_similar_image(foldername = foldername) 
