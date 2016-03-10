//HaloStats.Click Authenticator
//Author: Kasey Kolyno (Zno)
//Date: 3/2/2016
//Version: 0.1

//#include <stdio.h>
#include <iostream>
#include <string>
#include <inttypes.h>
#include <openssl\sha.h>
#include <sstream>
#include <iomanip>
#include <openssl/rsa.h>
#include <openssl/pem.h>
#include "openssl-1.0.2c\include\openssl\bn.h"

extern "C" { FILE __iob_func[3] = { *stdin,*stdout,*stderr }; }

using namespace std;
string sha256(const string str)
{
	unsigned char hash[SHA256_DIGEST_LENGTH];
	SHA256_CTX sha256;
	SHA256_Init(&sha256);
	SHA256_Update(&sha256, str.c_str(), str.size());
	SHA256_Final(hash, &sha256);
	stringstream ss;
	for (int i = 0; i < SHA256_DIGEST_LENGTH; i++)
	{
		ss << hex << setw(2) << setfill('0') << (int)hash[i];
	}
	return ss.str();
}

bool genKey(std::string& privKey, std::string& pubKey) {
	// TODO: add some error checking
	RSA* rsa = RSA_new();
	BIGNUM* bn = BN_new();
	BIO* bio_err = NULL;
	BN_set_word(bn, RSA_F4);
	RSA_generate_key_ex(rsa, 4096, bn, NULL);

	BIO* privKeyBuff = BIO_new(BIO_s_mem());
	BIO* pubKeyBuff = BIO_new(BIO_s_mem());
	PEM_write_bio_RSAPrivateKey(privKeyBuff, rsa, 0, 0, 0, 0, 0);
	PEM_write_bio_RSA_PUBKEY(pubKeyBuff, rsa); // RSA_PUBKEY includes some data that RSAPublicKey doesn't have

	char* privKeyData;
	char* pubKeyData;
	auto privKeySize = BIO_get_mem_data(privKeyBuff, &privKeyData);
	auto pubKeySize = BIO_get_mem_data(pubKeyBuff, &pubKeyData);

	privKey = std::string(privKeyData, privKeySize);
	pubKey = std::string(pubKeyData, pubKeySize);

	BIO_free_all(privKeyBuff);
	BIO_free_all(pubKeyBuff);
	BN_free(bn);
	RSA_free(rsa);
	return true;
}


int main(int argc, char *argv[])
{
	/*for (int i = 0; i < argc; i++) {
		std::cout << "argv[" << i << "]: " << argv[i] << "\n";
	}*/
	
	
	//printf("%d \n", strlen("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"));
	//printf("%d \n", strlen("MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAr6P/sE3m83HDj0eMqrpZalpHldtIhBLZo2Jk9kqKRkE5HzaaEJsdGrwBT5JOw0BgZ/TATZjjZ3HSShIgmAXaYMJeckIduAx6cQv4GM/bZ5q4Fr5/QIURuuit5CQZuoj0zPnq/4vlVJngSTI052ggKHtwf7BcSBYvFaBFspWnPSGLFY/PBg+ecKBiI8e4jlu3ZmUhbv+7Hf/30uKOF+tWx7y9kD5EEmNF6VLxxYXYKSpMcC7LKmnH3yXm/yi8RFxs3SM6bFLiUBGa3ycYK0jq1ioZ+yb7DJD5cu96bwIF/WcgrR2kP4bntG7ACKasEWj4IRXFoZFjS8Rzx6wT7C8u6x0PRx3dXjxP/d/ameOapexVv3sDY3Txc8ySSXhEshNhHSqyiY0Wnb625M53mQhnskeHuNYClCJX3YgWupaPS0oYkHNF8OnojvHPV9dLwWqQzJZIdDkT0LeJ5fg4yHm0fXGGIjDQuUHOfTH4RciFcj/QcG4WUYiHDfBeHq1OkBjmvkc0R/nSAQNalMM3rYi/mpgJgaYqT0ItVUwnfeE4DukxX02n04Vn+b7HwDRUOmkvOCfV8pgPCJx+QLLHXzaajlS9AGAcjOSR979lLTlwNH1sqLQ412Ydjn7poWTLHuGH3NNvB6Qn4OxZukLDGV4cz2o4svMRkQPzoLylcaD0KCMCAwEAAQ=="));

	std::string privKey;
	std::string pubKey;
	
	//genKey(privKey, pubKey);

	pubKey = "MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAnxyPA5TlZ61CHQgKgKNVqvJFqLoqVWoLb6V+WS1xQKGMr5d6gkk3fpwSOq0+z4Qf8OaYmZ49+CcWY/lMhNV3w0Np5zWvjwsMEYDdHcvM6J+kmy7INmet2vlfLmwO76Ap62+APsMWREnVXf2YWVqTKN5DyJofxBycdE/Te9GSzP3LFiGRzwIdDnZkcB5foyndJh4NWvgGPsKm8GTSCyvZri6FIsNykV6X8icUs5exBPf0Usq6xsek55D/ej6n6MxdsIiWOTqsUiLl40zv7UbpWzcJSeuXcgg52t3DleFGXJWDrfAevo3iqYkeWdIz2AvaQ1G9y9J9PZCxpNjpOD2B71ATcuAYYOXrWQZBaEuq/ZAV5RGapc40CxZBi+rGu8xVHaXAlynza1yomFfnURo9EGdx3E25DKbOy7QHouG0iluc3io+cwc+tprvNg8eMqgAEaCmPdpf5bNpAKkNk38p9v3CtLbNyshTfOT3AGAOAEAYCBzmyYtdgx+x3ieU+Zj2YCHdfHqMYlxAuXqKYPWEntT7CbDD2kyi/ayWJuW1uwedlqwaGqlThwNBg9BdDk53hqv+g/jjjLqdE4cUzONbiUQe8Wgat0R/70iWp3vsPOoMfFzQoXDAq2OuePoIVYaezJ/2C+LMn/MvpPfykV8yPIujLhh2Fba2jioHmhTqit8CAwEAAQ==";



	//std::cout << "Public Key: " << pubKey << endl << "Private Key: " << privKey << endl;
    //argv[1]
	string hashedUID = sha256(pubKey);
	string UID = hashedUID.substr(0, 16);
	
	// #### Might need for byte reversing of UID ###
	//char newUID [20]; //UID that is used to ban/whitelist users
	//sprintf(newUID, "%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s", UID[14], UID[15], UID[12], UID[13], UID[11], UID[10], UID[8], UID[9], UID[6], UID[7], UID[4], UID[5], UID[2], UID[3], UID[0], UID[1]);

	std::cout << UID << endl;
	

	getchar();
	return 0;
}