function g = sigmoidGradient(z)
% sigmoidGradient returns the gradient of the sigmoid function
% evaluated at z
% g = sigmoidGradient(z) computes the gradient of the sigmoid function
% evaluated at z. This should work regardless if z is a matrix or a
% vector. In particular, if z is a vector or matrix, we return
% the gradient for each element.

g = zeros(size(z));

% Compute the gradient of the sigmoid function evaluated at
% each value of z (z can be a matrix, vector or scalar).

g = sigmoid(z) .* (1 - sigmoid(z));

% =============================================================

end
