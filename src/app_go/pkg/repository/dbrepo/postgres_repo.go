package repository

import (
	"database/sql"
	"fmt"

	uuid58 "github.com/AlexanderMatveev/go-uuid-base58"
	"github.com/google/uuid"
)

const CODE_GENERATION_ATTEMPTS = 10

type PostgresDBRepo struct {
	DB *sql.DB
}

type DatabaseRepo interface {
	Connection() *sql.DB
	// AddUrl(company data.Company) (data.Company, error)
	FetchAvailableCode() (string, error)
	GenerateUrlCodes(codesCount int) (int, error)
	// InsertUserLogin(userLogin data.UserLogin) (data.UserLogin, error)
	// FetchLastUserLogins(userIds []int, companyId int, limit int) ([]data.UserLogin, error)
}

func (m *PostgresDBRepo) Connection() *sql.DB {
	return m.DB
}

func (m *PostgresDBRepo) FetchAvailableCode() (string, error) {
	var code string


	row := m.DB.QueryRow(`UPDATE url_code
	SET is_used=1
	WHERE id IN (select id from url_code where is_used=0 limit 1)
	RETURNING code`)
	
	if err := row.Scan(&code); err != nil {
		if err == sql.ErrNoRows {
				return "", fmt.Errorf("no short URL codes available")
			}
			return "", fmt.Errorf("could not fetch available URL code: %v", err)
		}

	return code, nil
}

func (m *PostgresDBRepo) GenerateUrlCodes(codesCount int) (int, error) {
	var codesGeneratedCount = 0
	for i := 0; i < codesCount; i++ {
		err := m.GenerateAndSaveOneCode(8)
		if err != nil {
			return codesGeneratedCount, err
		}
		codesGeneratedCount++
	}

	return codesGeneratedCount, nil
}

func (m *PostgresDBRepo) GenerateAndSaveOneCode(length int) error {
	for i := 0; i < CODE_GENERATION_ATTEMPTS; i++ {
		uuidBase58String, err := generateRandomCode(length)
		if err != nil {
			return err
		}
		_, err = m.DB.Exec("INSERT INTO url_code (code, is_used) VALUES (?, 0)", uuidBase58String)
		if err != nil {
			fmt.Println(err)
			continue
		}
		return nil
	}

	return nil
}

func generateRandomCode(length int) (string, error) {
	uuid := uuid.New()
	uuidBase58String, err := uuid58.ToBase58(uuid)
	if err != nil {
		return "", err
	}

	return uuidBase58String[:length], nil
}