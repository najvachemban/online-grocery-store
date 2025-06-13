from fastapi import FastAPI, Query, HTTPException
from sqlalchemy import create_engine, text
from pydantic import BaseModel
from sqlalchemy.exc import SQLAlchemyError



app = FastAPI()

DATABASE_URL = "mysql+pymysql://root@localhost/fastapi_db"
engine = create_engine(DATABASE_URL, echo=False, future=True)



app = FastAPI()

DATABASE_URL = "mysql+pymysql://root@localhost/fastapi_db"
engine = create_engine(DATABASE_URL)

@app.get("/recommended-items/")
def get_recommendations(month: int = Query(None, ge=1, le=12, description="Month number 1-12")):
    try:
        with engine.connect() as conn:
            if month:
                query = text("""
                    SELECT item_id, itemDescription, COUNT(*) as total_bought
                    FROM your_table_name
                    WHERE MONTH(Date) = :month
                    GROUP BY item_id, itemDescription
                    ORDER BY total_bought DESC
                    LIMIT 20
                """)
                result = conn.execute(query, {"month": month})
            else:
                query = text("""
                    SELECT item_id, itemDescription, COUNT(*) as total_bought
                    FROM your_table_name
                    GROUP BY item_id, itemDescription
                    ORDER BY total_bought DESC
                    LIMIT 20
                """)
                result = conn.execute(query)

            items = [
                {
                    "item_id": row[0],
                    "itemDescription": row[1],
                    "total_bought": row[2]
                } for row in result
            ]
            return {"recommendations": items}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))


class ItemDetail(BaseModel):
    item_id: int
    itemDescription: str

@app.get("/item/{item_id}", response_model=ItemDetail)
def get_item_by_id(item_id: int):
    try:
        with engine.connect() as conn:
            stmt = text("""
                SELECT item_id, itemDescription
                FROM your_table_name
                WHERE item_id = :item_id
                LIMIT 1
            """)

            result = conn.execute(stmt, {"item_id": item_id}).mappings().first()

            if result:
                return ItemDetail(
                    item_id=result["item_id"],
                    itemDescription=result["itemDescription"]
                )
            else:
                raise HTTPException(status_code=404, detail="Item not found")
    except SQLAlchemyError as e:
        raise HTTPException(status_code=500, detail=f"Database error: {str(e)}")
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
